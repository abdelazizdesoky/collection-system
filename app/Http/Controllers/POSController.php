<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Warehouse;
use App\Models\SaleInvoice;
use App\Models\SaleInvoiceItem;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Services\AccountingService;
use App\Services\InstallmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class POSController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService,
        protected InstallmentService $installmentService
    ) {}

    /**
     * Display the POS interface.
     */
    public function index(): View
    {
        $customers = Customer::orderBy('name')->get();
        $warehouses = Warehouse::where('is_active', true)->get();
        
        $products = Product::where('is_active', true)
            ->where('is_for_sale', true)
            ->with(['stockItems'])
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'code' => $p->code,
                    'selling_price' => (float)$p->selling_price,
                    'total_stock' => (float)$p->total_stock,
                    'is_low_stock' => $p->is_low_stock,
                    'warehouse_stocks' => $p->stockItems->pluck('quantity', 'warehouse_id')->toArray(),
                ];
            });

        return view('pos.index', compact('customers', 'warehouses', 'products'));
    }

    /**
     * Store a POS sale.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
            'payment_type' => 'required|in:cash,credit,installment',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
            'increase_percentage' => 'nullable|numeric|min:0',
            'duration_months' => 'nullable|integer|min:1',
            'start_date' => 'nullable|date',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            return DB::transaction(function () use ($validated, $request) {
                // Generate Code
                $code = 'POS-'.str_pad((SaleInvoice::withTrashed()->count() + 1), 6, '0', STR_PAD_LEFT);

                $invoice = SaleInvoice::create([
                    'code' => $code,
                    'customer_id' => $validated['customer_id'],
                    'warehouse_id' => $validated['warehouse_id'],
                    'invoice_date' => today(),
                    'payment_type' => $validated['payment_type'],
                    'notes' => $validated['notes'] ?? 'POS Sale',
                    'created_by' => auth()->id(),
                    'status' => 'draft',
                ]);

                $subtotal = 0;
                foreach ($validated['items'] as $item) {
                    $itemTotal = $item['quantity'] * $item['unit_price'];
                    $subtotal += $itemTotal;

                    // Stock Validation
                    $stock = StockItem::where('warehouse_id', $validated['warehouse_id'])
                        ->where('product_id', $item['product_id'])
                        ->value('quantity') ?? 0;

                    if ($item['quantity'] > $stock) {
                        $p = Product::find($item['product_id']);
                        throw new \Exception("الكمية غير متوفرة للصنف: " . ($p->name ?? ''));
                    }

                    SaleInvoiceItem::create([
                        'sale_invoice_id' => $invoice->id,
                        'product_id' => $item['product_id'],
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total' => $itemTotal,
                    ]);
                    
                    // Direct Stock Deduction for POS (Self-confirmed)
                    // We'll use a confirm-like logic but immediately
                    // Actually, let's keep POS invoices confirmed by default if it's cash/credit
                    // as requested "separate from collector approval" flow
                }

                $total = $subtotal;
                $paidAmount = $validated['paid_amount'] ?? 0;
                if ($validated['payment_type'] === 'cash') { $paidAmount = $total; }

                $invoice->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'paid_amount' => $paidAmount,
                    'remaining' => $total - $paidAmount,
                    'status' => 'confirmed', // POS is auto-confirmed
                ]);

                // 3. Record Transactions & Stock Movements
                foreach ($invoice->items as $item) {
                     $stockItem = StockItem::where('warehouse_id', $validated['warehouse_id'])
                        ->where('product_id', $item->product_id)
                        ->first();

                     if ($stockItem) {
                         $stockItem->decrement('quantity', $item->quantity);
                     }

                     StockMovement::create([
                        'warehouse_id' => $validated['warehouse_id'],
                        'product_id' => $item->product_id,
                        'movement_type' => 'out',
                        'quantity' => $item->quantity,
                        'reference_type' => 'sale_invoice',
                        'reference_id' => $invoice->id,
                        'note' => 'فاتورة بيع POS: '.$invoice->code,
                    ]);
                }

                // Financials
                $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                    'date' => $invoice->invoice_date,
                    'description' => 'فاتورة مبيعات POS رقم: '.$invoice->code,
                    'debit' => $total,
                    'credit' => 0,
                    'reference_type' => 'SaleInvoice',
                    'reference_id' => $invoice->id,
                ]);

                if ($paidAmount > 0) {
                    $payment = \App\Models\SalePayment::create([
                        'sale_invoice_id' => $invoice->id,
                        'amount' => $paidAmount,
                        'payment_date' => $invoice->invoice_date,
                        'notes' => 'دفعة نقدية مع فاتورة POS',
                    ]);

                    $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                        'date' => $invoice->invoice_date,
                        'description' => 'دفعة فاتورة POS رقم: '.$invoice->code,
                        'debit' => 0,
                        'credit' => $paidAmount,
                        'reference_type' => 'SalePayment',
                        'reference_id' => $payment->id,
                    ]);
                }

                // 4. Handle Installments (Syncing with SaleInvoice logical pattern)
                if ($validated['payment_type'] === 'installment') {
                    $this->installmentService->createPlan([
                        'customer_id' => $invoice->customer_id,
                        'invoice_no' => $invoice->code,
                        'total_amount' => $total,
                        'down_payment' => $paidAmount,
                        'increase_percentage' => $validated['increase_percentage'] ?? 0,
                        'duration_months' => $validated['duration_months'] ?? 12,
                        'start_date' => $validated['start_date'] ?? now(),
                        'notes' => $validated['notes'],
                    ]);
                }

                return redirect()->route('pos.index')->with('success', 'تم تسجيل عملية البيع بنجاح (فاتورة رقم: '.$code.')');
            });
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()])->withInput();
        }
    }
}
