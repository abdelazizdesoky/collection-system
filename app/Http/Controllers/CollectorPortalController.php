<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\CustomerAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View; // Add this import

class CollectorPortalController extends Controller
{
    /**
     * Display the collector's daily plans dashboard.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (! $collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        // Get today's plans for this collector
        $todayPlans = CollectionPlan::where('collector_id', $collector->id)
            ->whereDate('date', today())
            // ->where('status', 'open') // OPTIONAL: Uncomment to strictly hide closed plans
            // For now, we will show them but they will appear effectively empty or done.
            // If the user wants them GONE, we uncomment this.
            ->with(['items' => function ($query) {
                $query->with(['customer', 'collection']);
            }])
            ->get();

        // Calculate stats for each plan
        $todayPlans->each(function ($plan) {
            $plan->total_customers = $plan->items->count();
            $plan->collected_count = $plan->items->where('status', 'collected')->count();
            $plan->pending_count = $plan->items->where('status', 'pending')->count();
            $plan->total_expected = $plan->items->sum('expected_amount');
            $plan->total_collected = $plan->getTotalCollectedAmount();
            // Use Amount-based progress from Model
            $plan->progress_percentage = $plan->getProgressPercentage();
        });

        return view('collector-portal.dashboard', compact('todayPlans', 'collector'));
    }

    /**
     * Show a specific plan with its items.
     */
    public function showPlan(CollectionPlan $plan): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the plan belongs to this collector
        if ($plan->collector_id !== $collector->id) {
            abort(403, 'This plan does not belong to you.');
        }

        $plan->load(['items' => function ($query) {
            $query->with(['customer', 'collection'])
                  ->orderBy('priority');
        }]);

        return view('collector-portal.plan', compact('plan', 'collector'));
    }

    /**
     * Show the collection form for a specific plan item.
     */
    public function showCollectForm(CollectionPlanItem $planItem): View|RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the plan item belongs to this collector
        if ($planItem->collectionPlan->collector_id !== $collector->id) {
            abort(403, 'This item does not belong to your plan.');
        }

        // Prevent accessing form if already collected
        if ($planItem->status === 'collected') {
            if ($planItem->collection_id) {
                return redirect()->route('collector.receipt', $planItem->collection_id);
            }
            return redirect()->route('collector.plan', $planItem->collection_plan_id)
                ->with('warning', 'This item has already been collected.');
        }

        $planItem->load(['customer', 'collectionPlan']);

        // Generate next receipt number
        $lastReceipt = Collection::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $nextReceiptNum = 1;
        if ($lastReceipt && preg_match('/RCP-\d{8}(\d{4})/', $lastReceipt->receipt_no, $matches)) {
            $nextReceiptNum = (int) $matches[1] + 1;
        }
        $receiptNo = 'RCP-'.today()->format('Ymd').str_pad($nextReceiptNum, 4, '0', STR_PAD_LEFT);

        return view('collector-portal.collect', compact('planItem', 'collector', 'receiptNo'));
    }

    /**
     * Store the collection for a plan item.
     */
    public function storeCollection(Request $request, CollectionPlanItem $planItem): RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        if ($planItem->collectionPlan->collector_id !== $collector->id) {
            abort(403, 'This item does not belong to your plan.');
        }

        // Prevent double submission via Plan Item Status
        if ($planItem->status === 'collected') {
            return redirect()->route('collector.plan', $planItem->collection_plan_id)
                ->with('error', 'This item has already been collected.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'receipt_no' => 'required|string|unique:collections,receipt_no',
            'payment_type' => 'required|in:cash,cheque,bank_transfer',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|image|max:2048',
            // Cheque details
            'cheque_no' => 'required_if:payment_type,cheque|nullable|string',
            'bank_name' => 'required_if:payment_type,cheque,bank_transfer|nullable|string',
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            // Bank transfer details
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
        ], [
            'cheque_no.required_if' => 'رقم الشيك مطلوب عند الدفع بشيك',
            'bank_name.required_if' => 'اسم البنك مطلوب',
            'due_date.required_if' => 'تاريخ الاستحقاق مطلوب عند الدفع بشيك',
            'reference_no.required_if' => 'رقم المرجع مطلوب عند التحويل البنكي',
        ]);

        return DB::transaction(function () use ($planItem, $collector, $validated, $request) {
            // Lock the item row to prevent race conditions
            $lockedItem = CollectionPlanItem::where('id', $planItem->id)->lockForUpdate()->first();

            if ($lockedItem->status === 'collected') {
                throw new \Exception('This item has already been collected.');
            }

            // Handle attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('collection_proofs', 'public');
            }

            // 1. Create Collection
            $collection = Collection::create([
                'customer_id' => $planItem->customer_id,
                'collector_id' => $collector->id,
                'amount' => $validated['amount'],
                'payment_type' => $validated['payment_type'],
                'collection_date' => today(),
                'receipt_no' => $validated['receipt_no'],
                'notes' => $validated['notes'],
                'attachment' => $attachmentPath,
                'bank_name' => $validated['bank_name'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
            ]);

            // 2. If it's a cheque, create the cheque record
            if ($validated['payment_type'] === 'cheque') {
                \App\Models\Cheque::create([
                    'customer_id' => $planItem->customer_id,
                    'collection_id' => $collection->id,
                    'cheque_no' => $validated['cheque_no'],
                    'bank_name' => $validated['bank_name'],
                    'amount' => $validated['amount'],
                    'due_date' => $validated['due_date'],
                    'status' => 'pending',
                ]);
            }

            // 3. Update Plan Item
            $lockedItem->update([
                'status' => 'collected',
                'collection_id' => $collection->id,
            ]);

            // 4. Update Customer Account (Ledger) Logic
            $lastAccount = CustomerAccount::where('customer_id', $planItem->customer_id)
                ->orderBy('id', 'desc')
                ->first();

            $previousBalance = $lastAccount ? $lastAccount->balance : $planItem->customer->opening_balance;
            $newBalance = $previousBalance - $validated['amount'];

            $paymentTypeArabic = [
                'cash' => 'نقدي',
                'cheque' => 'شيك',
                'bank_transfer' => 'تحويل بنكي'
            ];

            CustomerAccount::create([
                'customer_id' => $planItem->customer_id,
                'date' => today(),
                'description' => "تحصيل ({$paymentTypeArabic[$validated['payment_type']]}) - إيصال رقم {$validated['receipt_no']}",
                'debit' => 0,
                'credit' => $validated['amount'],
                'balance' => $newBalance,
                'reference_type' => 'Collection',
                'reference_id' => $collection->id,
            ]);

            // 5. Check if Plan is Completed
            $plan = $planItem->collectionPlan;
            $pendingCount = $plan->items()->where('status', '!=', 'collected')->count();
            
            if ($pendingCount === 0) {
                $plan->update(['status' => 'closed']);
            }

            return redirect()->route('collector.receipt', $collection)
                ->with('success', 'تم تسجيل التحصيل وتحديث الرصيد بنجاح!');
        });
    }

    /**
     * Display the receipt for printing.
     */
    public function printReceipt(Collection $collection): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the collection belongs to this collector
        if ($collection->collector_id !== $collector->id) {
            abort(403, 'This receipt does not belong to you.');
        }

        $collection->load(['customer', 'collector', 'planItem']);

        return view('collector-portal.receipt', compact('collection', 'collector'));
    }
}
