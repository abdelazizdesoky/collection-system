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
     * Display the collector's daily dashboard with original tabbed style.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (!$collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        // Collection Plans for today
        $todayPlans = CollectionPlan::where('collector_id', $collector->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->latest()
            ->get();

        // Calculate statistics for each plan as expected by the view
        foreach ($todayPlans as $plan) {
            $plan->total_customers = $plan->items()->count();
            $plan->pending_count = $plan->items()->where('status', 'pending')->count();
            $plan->collected_count = $plan->items()->where('status', 'collected')->count();
            $plan->total_expected = (float) $plan->items()->sum('expected_amount');
            $plan->total_collected = (float) $plan->items()
                ->where('status', 'collected')
                ->join('collections', 'collection_plan_items.collection_id', '=', 'collections.id')
                ->sum('collections.amount');
            
            $plan->progress_percentage = $plan->total_customers > 0 
                ? round(($plan->collected_count / $plan->total_customers) * 100, 1) 
                : 0;
        }

        return view('collector-portal.dashboard', compact('collector', 'todayPlans'));
    }

    /**
     * Display the collector's visit dashboard with original tabbed style.
     */
    public function visitDashboard(): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (!$collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        // Visit Plans for today
        $todayPlans = \App\Models\VisitPlan::where('collector_id', $collector->id)
            ->whereIn('status', ['open', 'in_progress', 'closed'])
            ->latest()
            ->get();

        // Calculate statistics for each visit plan
        foreach ($todayPlans as $plan) {
            $plan->total_customers = $plan->items()->count();
            $plan->pending_count = $plan->items()->where('status', 'pending')->count();
            $plan->visited_count = $plan->items()->where('status', 'visited')->count();
            $plan->skipped_count = $plan->items()->where('status', 'skipped')->count();
            
            $plan->progress_percentage = $plan->total_customers > 0 
                ? round(($plan->visited_count / $plan->total_customers) * 100, 1) 
                : 0;
        }

        return view('collector-portal.visit-dashboard', compact('collector', 'todayPlans'));
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
                return redirect()->route('shared.receipt', $planItem->collection_id);
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

        $banks = \App\Models\Bank::orderBy('name')->get();

        return view('collector-portal.collect', compact('planItem', 'collector', 'receiptNo', 'banks'));
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
            // Cheque attachment validation
            'attachment' => [
                'nullable',
                'image',
                'max:2048',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->payment_type === 'cheque' && !$request->hasFile('attachment')) {
                        $fail('صورة الشيك مطلوبة عند الدفع بشيك.');
                    }
                },
            ],
            // Cheque details
            'cheque_no' => 'required_if:payment_type,cheque|nullable|string',
            'bank_name' => 'required_if:payment_type,cheque,bank_transfer|nullable|exists:banks,name',
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            // Bank transfer details
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
        ], [
            'cheque_no.required_if' => 'رقم الشيك مطلوب عند الدفع بشيك',
            'bank_name.required_if' => 'اسم البنك مطلوب',
            'bank_name.exists' => 'يرجى اختيار بنك صالح من القائمة',
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

            $description = "تحصيل ({$paymentTypeArabic[$validated['payment_type']]}) - إيصال رقم {$validated['receipt_no']}";

            // Installment Logic
            if ($request->has('is_installment') && $request->is_installment == 1) {
                $installment = $planItem->customer->due_installments->first();
                if ($installment) {
                    $installment->update([
                        'status' => 'paid',
                        'collection_id' => $collection->id
                    ]);
                    $description .= " - سداد قسط مستحق ({$installment->due_date->format('Y-m-d')})";
                }
            }

            CustomerAccount::create([
                'customer_id' => $planItem->customer_id,
                'date' => today(),
                'description' => $description,
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

            return redirect()->route('shared.receipt', $collection)
                ->with('success', 'تم تسجيل التحصيل وتحديث الرصيد بنجاح!');
        });
    }

    /**
     * Display the receipt for printing.
     */
    public function printReceipt(Collection $collection): View
    {
        $user = auth()->user();
        
        // Ownership check for collectors. Admins and Supervisors can see all.
        if ($user->hasRole('collector')) {
            $collector = $user->collector;
            if ($collection->collector_id !== $collector->id) {
                abort(403, 'This receipt does not belong to you.');
            }
        }

        if (!$collection->incrementPrintCount()) {
            return view('collector-portal.receipt-error', [
                'message' => 'لقد تجاوزت الحد المسموح لطباعة هذا الإيصال.',
                'collection' => $collection
            ]);
        }

        $collection->load(['customer', 'collector', 'planItem']);

        return view('collector-portal.receipt', compact('collection', 'collector'));
    }


    /**
     * Show a specific visit plan with its items.
     */
    public function showVisitPlan(\App\Models\VisitPlan $visitPlan): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the plan belongs to this collector
        if ($visitPlan->collector_id !== $collector->id) {
            abort(403, 'This plan does not belong to you.');
        }

        $visitPlan->load(['items' => function ($query) {
            $query->with(['customer', 'visit'])
                  ->orderBy('priority');
        }]);

        return view('collector-portal.visit-plan', compact('visitPlan', 'collector'));
    }

    // ... (intermediate methods)

    /**
     * Show the visit form for a specific plan item.
     */
    public function showVisitForm(\App\Models\VisitPlanItem $visitPlanItem): View|RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the plan item belongs to this collector
        if ($visitPlanItem->visitPlan->collector_id !== $collector->id) {
            abort(403, 'This item does not belong to your plan.');
        }

        // Prevent accessing form if already visited
        if ($visitPlanItem->status === 'visited') {
            return redirect()->route('collector.visit-plan', $visitPlanItem->visit_plan_id)
                ->with('warning', 'تمت زيارة هذا العميل بالفعل.');
        }

        $visitPlanItem->load(['customer', 'visitPlan']);

        // Generate next receipt number (for collection type)
        $lastReceipt = Collection::whereDate('created_at', today())
            ->orderBy('id', 'desc')
            ->first();

        $nextReceiptNum = 1;
        if ($lastReceipt && preg_match('/RCP-\d{8}(\d{4})/', $lastReceipt->receipt_no, $matches)) {
            $nextReceiptNum = (int) $matches[1] + 1;
        }
        $receiptNo = 'RCP-'.today()->format('Ymd').str_pad($nextReceiptNum, 4, '0', STR_PAD_LEFT);

        $banks = \App\Models\Bank::orderBy('name')->get();
        // Fetch active visit types. Assuming 'status' column or similar exists, or just all.
        // Prompt says "defined manually", suggesting a table. I saw VisitType model earlier.
        $visitTypes = \App\Models\VisitType::orderBy('id')->get(); // Using ID order or a specific 'sort_order' if available

        return view('collector-portal.visit-form', compact('visitPlanItem', 'collector', 'receiptNo', 'banks', 'visitTypes'));
    }

    /**
     * Store the visit for a plan item.
     */
    public function storeVisit(Request $request, \App\Models\VisitPlanItem $visitPlanItem): RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        if ($visitPlanItem->visitPlan->collector_id !== $collector->id) {
            abort(403, 'This item does not belong to your plan.');
        }

        if ($visitPlanItem->status === 'visited') {
            return redirect()->route('collector.visit-plan', $visitPlanItem->visit_plan_id)
                ->with('error', 'تمت زيارة هذا العميل بالفعل.');
        }

        $validated = $request->validate([
            'visit_type' => 'required|string', // Changed from enum to string/id validation later
            'notes' => 'nullable|string|max:1000',
            // Cheque attachment validation
            'attachment' => [
                'nullable',
                'image',
                'max:2048',
                function ($attribute, $value, $fail) use ($request) {
                    if (
                        $request->visit_type === 'collection' && 
                        $request->payment_type === 'cheque' && 
                        !$request->hasFile('attachment')
                    ) {
                        $fail('صورة الشيك مطلوبة عند الدفع بشيك.');
                    }
                },
            ],
            // Collection fields
            'amount' => 'required_if:visit_type,collection|nullable|numeric|min:0.01',
            'receipt_no' => 'required_if:visit_type,collection|nullable|string|unique:collections,receipt_no',
            'payment_type' => 'required_if:visit_type,collection|nullable|in:cash,cheque,bank_transfer',
            'cheque_no' => 'required_if:payment_type,cheque|nullable|string',
            'bank_name' => 'nullable|exists:banks,name', // Strict bank selection
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
            // Order fields
            'order_details' => 'required_if:visit_type,order|nullable|string|max:2000',
            'order_amount' => 'required_if:visit_type,order|nullable|numeric|min:0',
            // Issue fields
            'issue_description' => 'required_if:visit_type,issue|nullable|string|max:2000',
            'issue_status' => 'required_if:visit_type,issue|nullable|in:pending,resolved,escalated',
        ], [
            'amount.required_if' => 'المبلغ مطلوب عند التحصيل',
            'receipt_no.required_if' => 'رقم الإيصال مطلوب عند التحصيل',
            'payment_type.required_if' => 'نوع الدفع مطلوب عند التحصيل',
            'bank_name.exists' => 'يرجى اختيار بنك صالح من القائمة',
            'order_details.required_if' => 'تفاصيل الأوردر مطلوبة',
            'order_amount.required_if' => 'قيمة الأوردر مطلوبة',
            'issue_description.required_if' => 'وصف المشكلة مطلوب',
            'issue_status.required_if' => 'حالة المشكلة مطلوبة',
        ]);

        return DB::transaction(function () use ($visitPlanItem, $collector, $validated, $request) {
            // Map visit_type string to ID if possible
            $visitType = \App\Models\VisitType::where('name', $validated['visit_type'])->first();
            $visitTypeId = $visitType ? $visitType->id : null;

            // Lock the item row to prevent race conditions
            $lockedItem = \App\Models\VisitPlanItem::where('id', $visitPlanItem->id)->lockForUpdate()->first();

            if ($lockedItem->status === 'visited') {
                throw new \Exception('This item has already been visited.');
            }

            // Handle attachment
            $attachmentPath = null;
            if ($request->hasFile('attachment')) {
                $attachmentPath = $request->file('attachment')->store('visit_proofs', 'public');
            }

            $collectionId = null;

            // If visit type is collection, create Collection record
            if ($validated['visit_type'] === 'collection') {
                $collection = Collection::create([
                    'customer_id' => $visitPlanItem->customer_id,
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

                $collectionId = $collection->id;

                // If cheque, create cheque record
                if ($validated['payment_type'] === 'cheque') {
                    \App\Models\Cheque::create([
                        'customer_id' => $visitPlanItem->customer_id,
                        'collection_id' => $collection->id,
                        'cheque_no' => $validated['cheque_no'],
                        'bank_name' => $validated['bank_name'],
                        'amount' => $validated['amount'],
                        'due_date' => $validated['due_date'],
                        'status' => 'pending',
                    ]);
                }

                // Update Customer Account (Ledger)
                $lastAccount = CustomerAccount::where('customer_id', $visitPlanItem->customer_id)
                    ->orderBy('id', 'desc')
                    ->first();

                $previousBalance = $lastAccount ? $lastAccount->balance : $visitPlanItem->customer->opening_balance;
                $newBalance = $previousBalance - $validated['amount'];

                $paymentTypeArabic = [
                    'cash' => 'نقدي',
                    'cheque' => 'شيك',
                    'bank_transfer' => 'تحويل بنكي'
                ];

                $description = "تحصيل ({$paymentTypeArabic[$validated['payment_type']]}) - إيصال رقم {$validated['receipt_no']}";

                // Installment Logic
                if ($request->has('is_installment') && $request->is_installment == 1) {
                    $installment = $visitPlanItem->customer->due_installments->first();
                    if ($installment) {
                        $installment->update([
                            'status' => 'paid',
                            'collection_id' => $collection->id
                        ]);
                        $description .= " - سداد قسط مستحق ({$installment->due_date->format('Y-m-d')})";
                    }
                }

                CustomerAccount::create([
                    'customer_id' => $visitPlanItem->customer_id,
                    'date' => today(),
                    'description' => $description,
                    'debit' => 0,
                    'credit' => $validated['amount'],
                    'balance' => $newBalance,
                    'reference_type' => 'Collection',
                    'reference_id' => $collection->id,
                ]);
            }
                $orderAmount = null;
                $orderDetails = null;

                if ($validated['visit_type'] === 'order') {
                    $orderAmount  = $validated['order_amount'];
                    $orderDetails = $validated['order_details'];
                }
            // Create Visit record
            \App\Models\Visit::create([
                'visit_plan_item_id' => $visitPlanItem->id,
                'collector_id' => $collector->id,
                'customer_id' => $visitPlanItem->customer_id,
                'visit_type' => $validated['visit_type'],
                'visit_type_id' => $visitTypeId,
                'visit_time' => now(),
                'notes' => $validated['notes'],
                'attachment' => $attachmentPath,
                'collection_id' => $collectionId,
                'issue_description' => $validated['issue_description'] ?? null,
                'issue_status' => $validated['issue_status'] ?? null,
                'order_details' => $orderDetails,
                'order_amount'  => $orderAmount,
            ]);

            // Update Plan Item status
            $lockedItem->update(['status' => 'visited']);

            // Check if Plan is Completed
            $plan = $visitPlanItem->visitPlan;
            $pendingCount = $plan->items()->where('status', 'pending')->count();

            if ($pendingCount === 0) {
                $plan->update(['status' => 'closed']);
            } elseif ($plan->status === 'open') {
                $plan->update(['status' => 'in_progress']);
            }

            if ($validated['visit_type'] === 'collection' && $collectionId) {
                return redirect()->route('shared.receipt', $collectionId)
                    ->with('success', 'تم تسجيل التحصيل بنجاح!');
            }

            return redirect()->route('collector.visit-plan', $visitPlanItem->visit_plan_id)
                ->with('success', 'تم تسجيل الزيارة بنجاح!');
        });
    }

    /**
     * Show details for a specific visit.
     */
    public function showVisitDetails(\App\Models\Visit $visit): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        // Ensure the visit belongs to this collector
        if ($visit->collector_id !== $collector->id) {
            abort(403, 'This visit does not belong to you.');
        }

        $visit->load(['customer', 'collection', 'visitPlanItem.visitPlan']);

        return view('collector-portal.visit-details', compact('visit', 'collector'));
    }
}

