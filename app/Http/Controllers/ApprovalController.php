<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\SaleInvoice;
use App\Models\Visit;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\SalePayment;
use App\Services\AccountingService;
use App\Services\InstallmentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class ApprovalController extends Controller
{
    public function __construct(
        protected AccountingService $accountingService,
        protected InstallmentService $installmentService
    ) {}

    /**
     * Display a list of all pending ad-hoc actions.
     */
    public function index(): View
    {
        $pendingInvoices = SaleInvoice::where('status', 'pending_approval')->with(['customer', 'creator'])->latest()->get();
        $pendingCollections = Collection::where('status', 'pending')->with(['customer', 'collector'])->latest()->get();
        $pendingVisits = Visit::where('status', 'pending')->with(['customer', 'collector', 'visitType'])->latest()->get();

        return view('admin.approvals.index', compact('pendingInvoices', 'pendingCollections', 'pendingVisits'));
    }

    /**
     * Approve a pending action.
     */
    public function approve(Request $request, string $type, int $id): RedirectResponse
    {
        return DB::transaction(function () use ($type, $id, $request) {
            $user = auth()->user();

            if ($type === 'invoice') {
                $invoice = SaleInvoice::findOrFail($id);
                if ($invoice->status !== 'pending_approval') {
                    return back()->with('error', 'هذه الفاتورة ليست قيد المراجعة.');
                }

                // 1. Stock Availability Check
                foreach ($invoice->items as $item) {
                    $stockItem = StockItem::where('warehouse_id', $invoice->warehouse_id)
                        ->where('product_id', $item->product_id)
                        ->first();

                    if (!$stockItem || $stockItem->quantity < $item->quantity) {
                        return back()->with('error', "رصيد المنتج ({$item->product->name}) غير كافي.");
                    }
                }

                // 2. Perform Stock Reductions
                foreach ($invoice->items as $item) {
                    $stockItem = StockItem::where('warehouse_id', $invoice->warehouse_id)
                        ->where('product_id', $item->product_id)
                        ->first();
                    $stockItem->decrement('quantity', $item->quantity);

                    StockMovement::create([
                        'warehouse_id' => $invoice->warehouse_id,
                        'product_id' => $item->product_id,
                        'movement_type' => 'out',
                        'quantity' => $item->quantity,
                        'reference_type' => 'sale_invoice',
                        'reference_id' => $invoice->id,
                        'note' => 'موافقة فاتورة مندوب: ' . $invoice->code,
                    ]);
                }

                // 3. Record in Ledger
                $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                    'date' => $invoice->invoice_date,
                    'description' => 'فاتورة بيع مندوب (معتمدة) رقم: ' . $invoice->code,
                    'debit' => $invoice->total,
                    'credit' => 0,
                    'reference_type' => 'SaleInvoice',
                    'reference_id' => $invoice->id,
                ]);

                if ($invoice->paid_amount > 0) {
                    $payment = SalePayment::create([
                        'sale_invoice_id' => $invoice->id,
                        'customer_id' => $invoice->customer_id,
                        'amount' => $invoice->paid_amount,
                        'payment_date' => $invoice->invoice_date,
                        'notes' => 'دفعة مقدمة للفاتورة: ' . $invoice->code,
                    ]);

                    $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                        'date' => $invoice->invoice_date,
                        'description' => 'دفعة من فاتورة مندوب (معتمدة) رقم: ' . $invoice->code,
                        'debit' => 0,
                        'credit' => $invoice->paid_amount,
                        'reference_type' => 'SalePayment',
                        'reference_id' => $payment->id,
                    ]);
                }

                // 4. Handle Installments
                if ($invoice->payment_type === 'installment') {
                    $this->installmentService->createPlan([
                        'customer_id' => $invoice->customer_id,
                        'invoice_no' => $invoice->code,
                        'total_amount' => $invoice->total,
                        'down_payment' => $invoice->paid_amount,
                        'increase_percentage' => $invoice->installment_interest ?? 0,
                        'duration_months' => $invoice->installment_duration ?? 12,
                        'start_date' => $invoice->installment_start_date ?? now(),
                        'notes' => $invoice->notes,
                    ]);
                }

                $invoice->update([
                    'status' => 'confirmed',
                    'reviewed_by_id' => $user->id,
                    'reviewed_at' => now(),
                    'reviewer_notes' => $request->notes,
                ]);

            } elseif ($type === 'collection') {
                $collection = Collection::findOrFail($id);
                if ($collection->status !== 'pending') {
                    return back()->with('error', 'هذا التحصيل ليس قيد المراجعة.');
                }

                // Record in Ledger
                $this->accountingService->recordCustomerTransaction($collection->customer_id, [
                    'date' => $collection->collection_date,
                    'description' => 'تحصيل مندوب (معتمد) إيصال: ' . $collection->receipt_no,
                    'debit' => 0,
                    'credit' => $collection->amount,
                    'reference_type' => 'Collection',
                    'reference_id' => $collection->id,
                ]);

                // Clear due installments if applicable
                if ($collection->is_installment && $collection->customer->hasDueInstallments()) {
                    $remainingToDistribute = $collection->amount;
                    foreach ($collection->customer->due_installments as $inst) {
                        if ($remainingToDistribute <= 0) break;
                        $pay = min($remainingToDistribute, $inst->amount);
                        $inst->update(['status' => 'paid', 'paid_date' => now()]);
                        $remainingToDistribute -= $pay;
                    }
                }

                $collection->update([
                    'status' => 'approved',
                    'reviewed_by_id' => $user->id,
                    'reviewed_at' => now(),
                    'reviewer_notes' => $request->notes,
                ]);

            } elseif ($type === 'visit') {
                $visit = Visit::findOrFail($id);
                $visit->update([
                    'status' => 'approved',
                    'reviewed_by_id' => $user->id,
                    'reviewed_at' => now(),
                    'reviewer_notes' => $request->notes,
                ]);
            }

            return back()->with('success', 'تم اعتماد الإجراء بنجاح.');
        });
    }

    /**
     * Reject a pending action.
     */
    public function reject(Request $request, string $type, int $id): RedirectResponse
    {
        $user = auth()->user();
        $model = match($type) {
            'invoice' => SaleInvoice::class,
            'collection' => Collection::class,
            'visit' => Visit::class,
        };

        $item = $model::findOrFail($id);
        $item->update([
            'status' => 'rejected',
            'reviewed_by_id' => $user->id,
            'reviewed_at' => now(),
            'reviewer_notes' => $request->notes,
        ]);

        return back()->with('success', 'تم رفض الإجراء.');
    }
}
