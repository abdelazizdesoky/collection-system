<?php

namespace App\Http\Controllers\Purchasing;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseInvoiceItem;
use App\Models\PurchasePayment;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class PurchaseInvoiceController extends Controller
{
    public function __construct(protected \App\Services\AccountingService $accountingService) {}

    public function index(Request $request): View
    {
        $query = PurchaseInvoice::with(['supplier', 'warehouse', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function ($sq) use ($search) {
                        $sq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $invoices = $query->paginate(20);

        return view('purchasing.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $suppliers = Supplier::all();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->with('unit')->get();

        $nextCode = 'PUR-'.str_pad((PurchaseInvoice::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);

        return view('purchasing.invoices.create', compact('suppliers', 'warehouses', 'products', 'nextCode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:purchase_invoices,code',
            'supplier_id' => 'required|exists:suppliers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'invoice_date' => 'required|date',
            'due_date' => 'nullable|date',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_type' => 'required|in:cash,credit,installment',
            'paid_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            // Installment fields
            'increase_percentage' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
        ]);

        return DB::transaction(function () use ($validated) {
            $invoice = PurchaseInvoice::create([
                'code' => $validated['code'],
                'supplier_id' => $validated['supplier_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'] ?? null,
                'discount' => $validated['discount'] ?? 0,
                'tax' => $validated['tax'] ?? 0,
                'payment_type' => $validated['payment_type'],
                'installment_interest' => $validated['increase_percentage'] ?? 0,
                'installment_duration' => $validated['duration_months'] ?? 12,
                'installment_start_date' => $validated['start_date'] ?? null,
                'created_by' => auth()->id(),
                'status' => 'draft',
            ]);

            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $itemDiscount = $item['discount'] ?? 0;
                $itemTotal = ($item['quantity'] * $item['unit_price']) - $itemDiscount;
                $subtotal += $itemTotal;

                PurchaseInvoiceItem::create([
                    'purchase_invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $itemDiscount,
                    'total' => $itemTotal,
                ]);
            }

            $total = $subtotal - ($validated['discount'] ?? 0) + ($validated['tax'] ?? 0);
            $paidAmount = $validated['paid_amount'] ?? 0;

            if ($validated['payment_type'] === 'cash') {
                $paidAmount = $total;
            }

            $invoice->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'remaining' => $total - $paidAmount,
            ]);

            if ($paidAmount > 0) {
                PurchasePayment::create([
                    'purchase_invoice_id' => $invoice->id,
                    'supplier_id' => $validated['supplier_id'],
                    'amount' => $paidAmount,
                    'payment_date' => $validated['invoice_date'],
                    'notes' => 'دفعة مع الفاتورة',
                ]);
            }

            return redirect()->route('purchase-invoices.show', $invoice)
                ->with('success', 'تم إنشاء فاتورة الشراء بنجاح');
        });
    }

    public function show(PurchaseInvoice $purchaseInvoice): View
    {
        $purchaseInvoice->load(['supplier', 'warehouse', 'creator', 'items.product.unit', 'payments']);

        return view('purchasing.invoices.show', compact('purchaseInvoice'));
    }

    public function confirm(PurchaseInvoice $purchaseInvoice): RedirectResponse
    {
        if ($purchaseInvoice->status !== 'draft') {
            return back()->with('error', 'لا يمكن تأكيد هذه الفاتورة');
        }

        return DB::transaction(function () use ($purchaseInvoice) {
            $purchaseInvoice->update(['status' => 'confirmed']);

            // 1. Stock Movements
            foreach ($purchaseInvoice->items as $item) {
                $stockItem = StockItem::firstOrCreate(
                    [
                        'warehouse_id' => $purchaseInvoice->warehouse_id,
                        'product_id' => $item->product_id,
                    ],
                    ['quantity' => 0]
                );

                $stockItem->increment('quantity', $item->quantity);

                StockMovement::create([
                    'warehouse_id' => $purchaseInvoice->warehouse_id,
                    'product_id' => $item->product_id,
                    'movement_type' => 'in',
                    'quantity' => $item->quantity,
                    'reference_type' => 'purchase_invoice',
                    'reference_id' => $purchaseInvoice->id,
                    'note' => 'فاتورة شراء: '.$purchaseInvoice->code,
                ]);
            }

            // 2. Accounting Ledger (Credit the full total - Supplier is Liability)
            $this->accountingService->recordSupplierTransaction($purchaseInvoice->supplier_id, [
                'date' => $purchaseInvoice->invoice_date,
                'description' => 'فاتورة شراء رقم: '.$purchaseInvoice->code,
                'debit' => 0,
                'credit' => $purchaseInvoice->total,
                'reference_type' => 'PurchaseInvoice',
                'reference_id' => $purchaseInvoice->id,
            ]);

            // 3. Accounting Ledger (Debit the paid amount if any)
            if ($purchaseInvoice->paid_amount > 0) {
                $this->accountingService->recordSupplierTransaction($purchaseInvoice->supplier_id, [
                    'date' => $purchaseInvoice->invoice_date,
                    'description' => 'دفعة مقدمة من فاتورة رقم: '.$purchaseInvoice->code,
                    'debit' => $purchaseInvoice->paid_amount,
                    'credit' => 0,
                    'reference_type' => 'PurchasePayment',
                    'reference_id' => $purchaseInvoice->payments()->first()?->id ?? $purchaseInvoice->id,
                ]);
            }

            return back()->with('success', 'تم تأكيد الفاتورة وتحديث المخزون والحسابات بنجاح');
        });
    }

    public function addPayment(Request $request, PurchaseInvoice $purchaseInvoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$purchaseInvoice->remaining,
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment = PurchasePayment::create([
            'purchase_invoice_id' => $purchaseInvoice->id,
            'supplier_id' => $purchaseInvoice->supplier_id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->accountingService->recordSupplierTransaction($purchaseInvoice->supplier_id, [
            'date' => $validated['payment_date'],
            'description' => 'دفعة من فاتورة رقم: '.$purchaseInvoice->code,
            'debit' => $validated['amount'],
            'credit' => 0,
            'reference_type' => 'PurchasePayment',
            'reference_id' => $payment->id,
        ]);

        $purchaseInvoice->recalculateTotals();

        return back()->with('success', 'تم تسجيل الدفعة وتحديث الحساب بنجاح');
    }

    public function destroy(PurchaseInvoice $purchaseInvoice): RedirectResponse
    {
        if ($purchaseInvoice->status === 'confirmed') {
            return back()->with('error', 'لا يمكن حذف فاتورة مؤكدة');
        }

        $purchaseInvoice->delete();

        return redirect()->route('purchase-invoices.index')
            ->with('success', 'تم حذف فاتورة الشراء بنجاح');
    }
}
