<?php

namespace App\Http\Controllers\Sales;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Product;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceItem;
use App\Models\SalePayment;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\Warehouse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class SaleInvoiceController extends Controller
{
    public function __construct(
        protected \App\Services\AccountingService $accountingService,
        protected \App\Services\InstallmentService $installmentService
    ) {}

    public function index(Request $request): View
    {
        $query = SaleInvoice::with(['customer', 'warehouse', 'creator'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhereHas('customer', function ($cq) use ($search) {
                        $cq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('payment_type')) {
            $query->where('payment_type', $request->payment_type);
        }

        $invoices = $query->paginate(20);

        return view('sales.invoices.index', compact('invoices'));
    }

    public function create(): View
    {
        $customers = Customer::all();
        $warehouses = Warehouse::where('is_active', true)->get();
        $products = Product::where('is_active', true)->where('is_for_sale', true)->with('unit')->get();

        $nextCode = 'SAL-'.str_pad((SaleInvoice::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);

        return view('sales.invoices.create', compact('customers', 'warehouses', 'products', 'nextCode'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:sale_invoices,code',
            'customer_id' => 'required|exists:customers,id',
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
            'down_payment' => 'nullable|numeric|min:0',
            'increase_percentage' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
        ]);

        return DB::transaction(function () use ($validated) {
            $invoice = SaleInvoice::create([
                'code' => $validated['code'],
                'customer_id' => $validated['customer_id'],
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

                SaleInvoiceItem::create([
                    'sale_invoice_id' => $invoice->id,
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
                SalePayment::create([
                    'sale_invoice_id' => $invoice->id,
                    'customer_id' => $validated['customer_id'],
                    'amount' => $paidAmount,
                    'payment_date' => $validated['invoice_date'],
                    'notes' => 'دفعة مع الفاتورة',
                ]);
            }

            return redirect()->route('sale-invoices.show', $invoice)
                ->with('success', 'تم إنشاء فاتورة البيع بنجاح');
        });
    }

    public function show(SaleInvoice $saleInvoice): View
    {
        $saleInvoice->load(['customer', 'warehouse', 'creator', 'items.product.unit', 'payments']);

        return view('sales.invoices.show', compact('saleInvoice'));
    }

    public function confirm(SaleInvoice $saleInvoice): RedirectResponse
    {
        if ($saleInvoice->status !== 'draft') {
            return back()->with('error', 'لا يمكن تأكيد هذه الفاتورة');
        }

        // Check stock availability
        foreach ($saleInvoice->items as $item) {
            $stockItem = StockItem::where('warehouse_id', $saleInvoice->warehouse_id)
                ->where('product_id', $item->product_id)
                ->first();

            if (! $stockItem || $stockItem->quantity < $item->quantity) {
                $productName = $item->product->name ?? 'منتج غير معروف';

                return back()->with('error', "رصيد المنتج ({$productName}) غير كافي في المخزن");
            }
        }

        return DB::transaction(function () use ($saleInvoice) {
            $saleInvoice->update(['status' => 'confirmed']);

            // 1. Stock Movements
            foreach ($saleInvoice->items as $item) {
                $stockItem = StockItem::where('warehouse_id', $saleInvoice->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                $stockItem->decrement('quantity', $item->quantity);

                StockMovement::create([
                    'warehouse_id' => $saleInvoice->warehouse_id,
                    'product_id' => $item->product_id,
                    'movement_type' => 'out',
                    'quantity' => $item->quantity,
                    'reference_type' => 'sale_invoice',
                    'reference_id' => $saleInvoice->id,
                    'note' => 'فاتورة بيع: '.$saleInvoice->code,
                ]);
            }

            // 2. Accounting Ledger (Debit the full total)
            $this->accountingService->recordCustomerTransaction($saleInvoice->customer_id, [
                'date' => $saleInvoice->invoice_date,
                'description' => 'فاتورة بيع رقم: '.$saleInvoice->code,
                'debit' => $saleInvoice->total,
                'credit' => 0,
                'reference_type' => 'SaleInvoice',
                'reference_id' => $saleInvoice->id,
            ]);

            // 3. Accounting Ledger (Credit the paid amount if any)
            if ($saleInvoice->paid_amount > 0) {
                $this->accountingService->recordCustomerTransaction($saleInvoice->customer_id, [
                    'date' => $saleInvoice->invoice_date,
                    'description' => 'دفعة مقدمة من فاتورة رقم: '.$saleInvoice->code,
                    'debit' => 0,
                    'credit' => $saleInvoice->paid_amount,
                    'reference_type' => 'SalePayment',
                    'reference_id' => $saleInvoice->payments()->first()?->id ?? $saleInvoice->id,
                ]);
            }

            // 4. Installments Generation
            if ($saleInvoice->payment_type === 'installment') {
                // We use the remaining amount for the installment plan
                // But wait, the InstallmentService takes total_amount and handles down_payment
                $this->installmentService->createPlan([
                    'customer_id' => $saleInvoice->customer_id,
                    'invoice_no' => $saleInvoice->code,
                    'total_amount' => $saleInvoice->total,
                    'down_payment' => $saleInvoice->paid_amount,
                    'increase_percentage' => $saleInvoice->installment_interest ?? 0,
                    'duration_months' => $saleInvoice->installment_duration ?? 12,
                    'start_date' => $saleInvoice->installment_start_date ?? $saleInvoice->invoice_date,
                    'notes' => $saleInvoice->notes,
                ]);
            }

            return back()->with('success', 'تم تأكيد الفاتورة وتحديث المخزون والحسابات بنجاح');
        });
    }

    public function addPayment(Request $request, SaleInvoice $saleInvoice): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01|max:'.$saleInvoice->remaining,
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $payment = SalePayment::create([
            'sale_invoice_id' => $saleInvoice->id,
            'customer_id' => $saleInvoice->customer_id,
            'amount' => $validated['amount'],
            'payment_date' => $validated['payment_date'],
            'notes' => $validated['notes'] ?? null,
        ]);

        $this->accountingService->recordCustomerTransaction($saleInvoice->customer_id, [
            'date' => $validated['payment_date'],
            'description' => 'دفعة من فاتورة رقم: '.$saleInvoice->code,
            'debit' => 0,
            'credit' => $validated['amount'],
            'reference_type' => 'SalePayment',
            'reference_id' => $payment->id,
        ]);

        $saleInvoice->recalculateTotals();

        return back()->with('success', 'تم تسجيل الدفعة وتحديث الحساب بنجاح');
    }

    public function print(SaleInvoice $saleInvoice): View
    {
        $saleInvoice->load(['customer', 'warehouse', 'creator', 'items.product.unit', 'payments']);

        return view('sales.invoices.print', compact('saleInvoice'));
    }

    public function destroy(SaleInvoice $saleInvoice): RedirectResponse
    {
        if ($saleInvoice->status === 'confirmed') {
            return back()->with('error', 'لا يمكن حذف فاتورة مؤكدة');
        }

        $saleInvoice->delete();

        return redirect()->route('sale-invoices.index')
            ->with('success', 'تم حذف فاتورة البيع بنجاح');
    }
}
