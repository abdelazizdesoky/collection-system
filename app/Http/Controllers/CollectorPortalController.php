<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\CustomerAccount;
use App\Models\Issue;
use App\Models\IssueHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use Illuminate\View\View; // Add this import

class CollectorPortalController extends Controller
{
    public function __construct(
        protected \App\Services\AccountingService $accountingService,
        protected \App\Services\InstallmentService $installmentService
    ) {}

    /**
     * Display the collector's daily dashboard with original tabbed style.
     */
    public function dashboard(): View
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (! $collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        // Collection Plans for today
        $todayPlans = CollectionPlan::where('collector_id', $collector->id)
            ->whereIn('status', ['open', 'in_progress'])
            ->forDate(today())
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

        if (! $collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        // Visit Plans for today
        $todayPlans = \App\Models\VisitPlan::where('collector_id', $collector->id)
            ->whereIn('status', ['open', 'in_progress', 'closed'])
            ->forDate(today())
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
                    if ($request->payment_type === 'cheque' && ! $request->hasFile('attachment')) {
                        $fail('صورة الشيك مطلوبة عند الدفع بشيك.');
                    }
                },
            ],
            // Cheque details
            'cheque_no' => 'required_if:payment_type,cheque|nullable|string',
            'bank_name' => 'nullable|exists:banks,name',
            'bank_name_cheque' => 'nullable|exists:banks,name',
            'bank_name_transfer' => 'nullable|exists:banks,name',
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            // Bank transfer details
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
        ], [
            'cheque_no.required_if' => 'رقم الشيك مطلوب عند الدفع بشيك',
            'bank_name.required_if' => 'اسم البنك مطلوب',
            'bank_name_cheque.required_if' => 'اسم البنك مطلوب',
            'bank_name_transfer.required_if' => 'اسم البنك مطلوب',
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
            $bankName = $validated['bank_name'] ?? ($validated['bank_name_cheque'] ?? ($validated['bank_name_transfer'] ?? null));

            $collection = Collection::create([
                'customer_id' => $planItem->customer_id,
                'collector_id' => $collector->id,
                'amount' => $validated['amount'],
                'payment_type' => $validated['payment_type'],
                'collection_date' => today(),
                'receipt_no' => $validated['receipt_no'],
                'notes' => $validated['notes'],
                'attachment' => $attachmentPath,
                'bank_name' => $bankName,
                'reference_no' => $validated['reference_no'] ?? null,
            ]);

            // 2. If it's a cheque, create the cheque record
            if ($validated['payment_type'] === 'cheque') {
                \App\Models\Cheque::create([
                    'customer_id' => $planItem->customer_id,
                    'collection_id' => $collection->id,
                    'cheque_no' => $validated['cheque_no'],
                    'bank_name' => $bankName,
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
                'bank_transfer' => 'تحويل بنكي',
            ];

            $description = "تحصيل ({$paymentTypeArabic[$validated['payment_type']]}) - إيصال رقم {$validated['receipt_no']}";

            // Installment Logic
            if ($request->has('is_installment') && $request->is_installment == 1) {
                $installment = $planItem->customer->due_installments->first();
                if ($installment) {
                    $installment->update([
                        'status' => 'paid',
                        'collection_id' => $collection->id,
                    ]);
                    $description .= " - سداد قسط مستحق ({$installment->due_date->format('Y-m-d')})";
                }
            }

            // Record to Customer Ledger ONLY if NOT a cheque
            // Cheques will be recorded only when cleared/collected
            if ($validated['payment_type'] !== 'cheque') {
                $this->accountingService->recordCustomerTransaction($planItem->customer_id, [
                    'date' => today(),
                    'description' => $description,
                    'debit' => 0,
                    'credit' => $validated['amount'],
                    'reference_type' => 'Collection',
                    'reference_id' => $collection->id,
                ]);
            }

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

        if (! $collection->incrementPrintCount()) {
            return view('collector-portal.receipt-error', [
                'message' => 'لقد تجاوزت الحد المسموح لطباعة هذا الإيصال.',
                'collection' => $collection,
            ]);
        }

        $collection->load(['customer', 'collector', 'planItem', 'visit.visitPlanItem', 'cheque']);
        $collector = $collection->collector;

        // Determine redirection URL after printing
        $returnUrl = route('collector.dashboard');
        if ($collection->visit) {
            $returnUrl = route('visit.details', $collection->visit->id);
        } elseif ($collection->planItem) {
            $returnUrl = route('collector.plan', $collection->planItem->collection_plan_id);
        }

        return view('collector-portal.receipt', compact('collection', 'collector', 'returnUrl'));
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

        // Fetch unresolved issues for this customer
        $activeIssues = \App\Models\Issue::where('customer_id', $visitPlanItem->customer_id)
            ->where('status', '!=', 'resolved')
            ->latest()
            ->get();

        return view('collector-portal.visit-form', compact('visitPlanItem', 'collector', 'receiptNo', 'banks', 'visitTypes', 'activeIssues'));
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
                        ! $request->hasFile('attachment')
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
            'bank_name' => 'nullable|exists:banks,name',
            'bank_name_cheque' => 'nullable|exists:banks,name',
            'bank_name_transfer' => 'nullable|exists:banks,name',
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
            // Order fields
            'order_details' => 'required_if:visit_type,order|nullable|string|max:2000',
            'order_amount' => 'required_if:visit_type,order|nullable|numeric|min:0',
            // Issue fields
            'issue_action_type' => 'required_if:visit_type,issue|nullable|in:new,existing',
            'selected_issue_id' => 'required_if:issue_action_type,existing|nullable|exists:issues,id',
            'followup_comment' => 'required_if:issue_action_type,existing|nullable|string|max:1000',
            'followup_status' => 'required_if:issue_action_type,existing|nullable|in:pending,processing,resolved,closed',
            'issue_description' => [
                'nullable', 'string', 'max:2000',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->visit_type === 'issue' && $request->issue_action_type === 'new' && empty($value)) {
                        $fail('وصف المشكلة مطلوب عند تسجيل مشكلة جديدة.');
                    }
                },
            ],
            'issue_status' => 'required_if:issue_action_type,new|nullable|in:pending,resolved,escalated,closed',
        ], [
            'amount.required_if' => 'المبلغ مطلوب عند التحصيل',
            'receipt_no.required_if' => 'رقم الإيصال مطلوب عند التحصيل',
            'payment_type.required_if' => 'نوع الدفع مطلوب عند التحصيل',
            'bank_name.exists' => 'يرجى اختيار بنك صالح من القائمة',
            'order_details.required_if' => 'تفاصيل الأوردر مطلوبة',
            'order_amount.required_if' => 'قيمة الأوردر مطلوبة',
            'issue_action_type.required_if' => 'يرجى اختيار نوع الإجراء للمشكلة (جديدة/متابعة)',
            'selected_issue_id.required_if' => 'يرجى اختيار المشكلة المراد متابعتها',
            'followup_comment.required_if' => 'تعليق المتابعة مطلوب',
            'issue_description.required_if' => 'وصف المشكلة مطلوب',
            'issue_status.required_if' => 'حالة المشكلة مطلوبة',
        ]);

        // Secondary check: if visit_type is not issue, clear issue-related validated data
        if ($request->visit_type !== 'issue') {
            $validated['issue_action_type'] = null;
            $validated['issue_description'] = null;
            $validated['issue_status'] = null;
        }

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
                $bankName = $validated['bank_name'] ?? ($validated['bank_name_cheque'] ?? ($validated['bank_name_transfer'] ?? null));

                $collection = Collection::create([
                    'customer_id' => $visitPlanItem->customer_id,
                    'collector_id' => $collector->id,
                    'amount' => $validated['amount'],
                    'payment_type' => $validated['payment_type'],
                    'collection_date' => today(),
                    'receipt_no' => $validated['receipt_no'],
                    'notes' => $validated['notes'],
                    'attachment' => $attachmentPath,
                    'bank_name' => $bankName,
                    'reference_no' => $validated['reference_no'] ?? null,
                ]);

                $collectionId = $collection->id;

                // If cheque, create cheque record
                if ($validated['payment_type'] === 'cheque') {
                    \App\Models\Cheque::create([
                        'customer_id' => $visitPlanItem->customer_id,
                        'collection_id' => $collection->id,
                        'cheque_no' => $validated['cheque_no'],
                        'bank_name' => $bankName,
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
                    'bank_transfer' => 'تحويل بنكي',
                ];

                $description = "تحصيل ({$paymentTypeArabic[$validated['payment_type']]}) - إيصال رقم {$validated['receipt_no']}";

                // Installment Logic
                if ($request->has('is_installment') && $request->is_installment == 1) {
                    $installment = $visitPlanItem->customer->due_installments->first();
                    if ($installment) {
                        $installment->update([
                            'status' => 'paid',
                            'collection_id' => $collection->id,
                        ]);
                        $description .= " - سداد قسط مستحق ({$installment->due_date->format('Y-m-d')})";
                    }
                }
                // Record to Customer Ledger ONLY if NOT a cheque
                // Cheques will be recorded only when cleared/collected
                if ($validated['payment_type'] !== 'cheque') {
                    $this->accountingService->recordCustomerTransaction($visitPlanItem->customer_id, [
                        'date' => today(),
                        'description' => $description,
                        'debit' => 0,
                        'credit' => $validated['amount'],
                        'reference_type' => 'Collection',
                        'reference_id' => $collection->id,
                    ]);
                }
            }
            $orderAmount = null;
            $orderDetails = null;

            if ($validated['visit_type'] === 'order') {
                $orderAmount = $validated['order_amount'];
                $orderDetails = $validated['order_details'];
            }
            // Create the visit record
            $visit = \App\Models\Visit::create([
                'collector_id' => $collector->id,
                'customer_id' => $visitPlanItem->customer_id,
                'visit_plan_item_id' => $visitPlanItem->id,
                'visit_type' => $validated['visit_type'],
                'visit_type_id' => $visitTypeId,
                'visit_time' => now(),
                'notes' => $validated['notes'],
                'collection_id' => $collectionId,
                'attachment' => $attachmentPath,
                'order_details' => $orderDetails,
                'order_amount' => $orderAmount,
                'issue_description' => $validated['visit_type'] === 'issue'
                    ? ($validated['issue_action_type'] === 'new' ? $validated['issue_description'] : $validated['followup_comment'])
                    : null,
                'issue_status' => $validated['visit_type'] === 'issue'
                    ? ($validated['issue_action_type'] === 'new' ? $validated['issue_status'] : $validated['followup_status'])
                    : null,
            ]);

            // Process Issue updates or creation
            if ($validated['visit_type'] === 'issue') {
                if ($validated['issue_action_type'] === 'existing') {
                    $issue = Issue::findOrFail($validated['selected_issue_id']);
                    $oldStatus = $issue->status;

                    $updateData = ['status' => $validated['followup_status']];
                    if ($validated['followup_status'] === 'closed') {
                        $updateData['closure_reason'] = $validated['followup_comment'];
                    }

                    $issue->update($updateData);

                    IssueHistory::create([
                        'issue_id' => $issue->id,
                        'user_id' => auth()->id(),
                        'visit_id' => $visit->id,
                        'comment' => $validated['followup_comment'],
                        'old_status' => $oldStatus,
                        'new_status' => $validated['followup_status'],
                    ]);

                    // Link visit to the head issue record
                    $visit->update(['issue_id' => $issue->id]);
                } else {
                    $issue = Issue::create([
                        'customer_id' => $visitPlanItem->customer_id,
                        'collector_id' => $collector->id,
                        'visit_id' => $visit->id,
                        'description' => $validated['issue_description'],
                        'status' => $validated['issue_status'] ?? 'pending',
                        'closure_reason' => ($validated['issue_status'] ?? 'pending') === 'closed' ? $validated['issue_description'] : null,
                    ]);

                    // Link visit to the newly created head issue record
                    $visit->update(['issue_id' => $issue->id]);
                }
            }

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
        $visit->load(['visitPlanItem.customer', 'visitPlanItem.visitPlan', 'collection', 'issue']);

        return view('collector-portal.visit-details', compact('visit'));
    }

    /**
     * Update an existing issue from the collector portal.
     */
    public function updateIssue(Request $request, Issue $issue): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
            'status' => 'required|string|in:pending,processing,resolved',
        ]);

        $oldStatus = $issue->status;
        $issue->update(['status' => $validated['status']]);

        IssueHistory::create([
            'issue_id' => $issue->id,
            'user_id' => auth()->id(),
            'comment' => $validated['comment'],
            'old_status' => $oldStatus,
            'new_status' => $validated['status'],
        ]);

        return redirect()->back()->with('success', 'تم تحديث المشكلة بنجاح.');
    }

    /**
     * Show the form for creating a new sale invoice specifically for collectors.
     */
    public function createSaleInvoice($customerId = null): View|RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (! $collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        $customers = \App\Models\Customer::where('collector_id', $collector->id)->get();
        $warehouses = \App\Models\Warehouse::where('is_active', true)->get();
        $products = \App\Models\Product::where('is_active', true)
            ->where('is_for_sale', true)
            ->with(['stockItems'])
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->name,
                    'selling_price' => $p->selling_price,
                    'total_stock' => (float)$p->total_stock,
                    'min_stock' => (float)$p->min_stock,
                    'is_low_stock' => $p->is_low_stock,
                    'warehouse_stocks' => $p->stockItems->pluck('quantity', 'warehouse_id')->toArray(),
                ];
            });

        return view('collector-portal.sales.create', compact('customers', 'warehouses', 'products', 'customerId'));
    }

    /**
     * Store a newly created sale invoice from the collector portal.
     */
    public function storeSaleInvoice(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $collector = $user->collector;

        if (! $collector) {
            abort(403, 'You are not associated with a collector profile.');
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'warehouse_id' => 'required|exists:warehouses,id',
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

        // Ensure customer belongs to collector
        $customer = \App\Models\Customer::findOrFail($validated['customer_id']);
        if ($customer->collector_id !== $collector->id) {
            abort(403, 'You can only create invoices for your assigned customers.');
        }

        return DB::transaction(function () use ($validated, $user, $request, $collector) {
            $code = 'SAL-C-'.str_pad((\App\Models\SaleInvoice::withTrashed()->count() + 1), 5, '0', STR_PAD_LEFT);

            $invoice = \App\Models\SaleInvoice::create([
                'code' => $code,
                'customer_id' => $validated['customer_id'],
                'warehouse_id' => $validated['warehouse_id'],
                'invoice_date' => today(),
                'due_date' => null,
                'discount' => 0,
                'tax' => 0,
                'payment_type' => $validated['payment_type'],
                'notes' => $validated['notes'] ?? null,
                'installment_interest' => $validated['increase_percentage'] ?? 0,
                'installment_duration' => $validated['duration_months'] ?? 12,
                'installment_start_date' => $validated['start_date'] ?? null,
                'created_by' => $user->id,
                'status' => 'draft', // Standardizes starting status
            ]);

            // 2. Add Invoice Items
            $subtotal = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = ($item['quantity'] * $item['unit_price']) - ($item['discount'] ?? 0);
                $itemDiscount = $item['discount'] ?? 0;
                $subtotal += $itemTotal;

                // Backend Stock Check
                $stockInWarehouse = \App\Models\StockItem::where('warehouse_id', $validated['warehouse_id'])
                    ->where('product_id', $item['product_id'])
                    ->value('quantity') ?? 0;

                if ($item['quantity'] > $stockInWarehouse) {
                    $productName = \App\Models\Product::find($item['product_id'])?->name ?? 'المنتج';
                    throw new \Exception("عذراً، الكمية المطلوبة من ($productName) غير متوفرة في المستودع المختار. (المتاح: $stockInWarehouse)");
                }

                \App\Models\SaleInvoiceItem::create([
                    'sale_invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'discount' => $itemDiscount,
                    'total' => $itemTotal,
                ]);
            }

            $total = $subtotal;
            $paidAmount = $validated['paid_amount'] ?? 0;

            if ($validated['payment_type'] === 'cash') {
                $paidAmount = $total;
            }

            // FORCE ALL field invoices to be pending
            $isAdhoc = true;

            $invoice->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'paid_amount' => $paidAmount,
                'remaining' => $total - $paidAmount,
                'status' => $isAdhoc ? 'pending_approval' : 'confirmed',
                'is_adhoc' => $isAdhoc,
            ]);

            // If it's ad-hoc, we STOP here. Admin will confirm it later.
            if ($isAdhoc) {
                return redirect()->route('collector.adhoc')
                    ->with('success', 'تم إرسال الفاتورة للمراجعة بنجاح! ستظهر في حساب العميل بعد موافقة الإدارة.');
            }

            // 1. Stock Movements
            foreach ($invoice->items as $item) {
                $stockItem = \App\Models\StockItem::where('warehouse_id', $invoice->warehouse_id)
                    ->where('product_id', $item->product_id)
                    ->first();

                if ($stockItem) {
                    $stockItem->decrement('quantity', $item->quantity);

                    \App\Models\StockMovement::create([
                        'warehouse_id' => $invoice->warehouse_id,
                        'product_id' => $item->product_id,
                        'movement_type' => 'out',
                        'quantity' => $item->quantity,
                        'reference_type' => 'sale_invoice',
                        'reference_id' => $invoice->id,
                        'note' => 'فاتورة بيع مندوب: '.$invoice->code,
                    ]);
                }
            }

            // 2. Accounting Ledger (Debit the full total)
            $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                'date' => $invoice->invoice_date,
                'description' => 'فاتورة بيع مندوب رقم: '.$invoice->code,
                'debit' => $invoice->total,
                'credit' => 0,
                'reference_type' => 'SaleInvoice',
                'reference_id' => $invoice->id,
            ]);

            // 3. Accounting Ledger (Credit the paid amount if any)
            if ($paidAmount > 0) {
                $payment = \App\Models\SalePayment::create([
                    'sale_invoice_id' => $invoice->id,
                    'customer_id' => $invoice->customer_id,
                    'amount' => $paidAmount,
                    'payment_date' => $invoice->invoice_date,
                    'notes' => 'دفعة مع فاتورة المندوب',
                ]);

                $this->accountingService->recordCustomerTransaction($invoice->customer_id, [
                    'date' => $invoice->invoice_date,
                    'description' => 'دفعة فاتورة مندوب رقم: '.$invoice->code,
                    'debit' => 0,
                    'credit' => $paidAmount,
                    'reference_type' => 'SalePayment',
                    'reference_id' => $payment->id,
                ]);

                // Create a Collection record (for reports)
                Collection::create([
                    'customer_id' => $invoice->customer_id,
                    'collector_id' => $collector->id,
                    'amount' => $paidAmount,
                    'payment_type' => 'cash',
                    'collection_date' => $invoice->invoice_date,
                    'receipt_no' => 'RCP-S-'.$invoice->code,
                    'notes' => 'تحصيل فاتورة مبيعات ميدانية',
                ]);
            }

            // 4. Installments Generation
            if ($invoice->payment_type === 'installment') {
                $this->installmentService->createPlan([
                    'customer_id' => $invoice->customer_id,
                    'invoice_no' => $invoice->code,
                    'total_amount' => $invoice->total,
                    'down_payment' => $invoice->paid_amount,
                    'increase_percentage' => $invoice->installment_interest ?? 0,
                    'duration_months' => $invoice->installment_duration ?? 12,
                    'start_date' => $invoice->installment_start_date ?? $invoice->invoice_date,
                    'notes' => $invoice->notes,
                ]);
            }

            return redirect()->route('collector.dashboard')
                ->with('success', 'تم تسجيل الفاتورة وتحديث المخزون والحسابات بنجاح');
        });
    }

    /**
     * Ad-hoc actions landing page.
     */
    public function adhocLanding()
    {
        $user = auth()->user();
        $collector = $user->collector;
        if (!$collector) {
            return back()->with('error', 'عذراً، لم يتم ربط هذا الحساب ببيانات مندوب.');
        }

        // Get customers explicitly assigned OR had plans with this collector
        $customers = \App\Models\Customer::where('collector_id', $collector->id)
            ->orWhereHas('collectionPlanItems', function($q) use ($collector) {
                $q->whereHas('collectionPlan', function($qp) use ($collector) {
                    $qp->where('collector_id', $collector->id);
                });
            })
            ->with(['collectionPlanItems', 'visitPlanItems'])
            ->get();

        return view('collector-portal.adhoc', compact('collector', 'customers'));
    }

    /**
     * Show ad-hoc collection form.
     */
    public function showAdhocCollectForm(Customer $customer): View
    {
        $collector = auth()->user()->collector;
        
        // Ownership check
        if ($customer->collector_id !== $collector->id) {
            abort(403);
        }

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

        return view('collector-portal.adhoc.collect', compact('customer', 'collector', 'receiptNo', 'banks'));
    }

    /**
     * Store ad-hoc collection (Pending Approval).
     */
    public function storeAdhocCollection(Request $request, Customer $customer): RedirectResponse
    {
        $collector = auth()->user()->collector;
        if ($customer->collector_id !== $collector->id) { abort(403); }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'receipt_no' => 'required|string|unique:collections,receipt_no',
            'payment_type' => 'required|in:cash,cheque,bank_transfer',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|image|max:2048',
            'cheque_no' => 'required_if:payment_type,cheque|nullable|string',
            'bank_name' => 'nullable|string',
            'due_date' => 'required_if:payment_type,cheque|nullable|date',
            'reference_no' => 'required_if:payment_type,bank_transfer|nullable|string',
        ]);

        return DB::transaction(function () use ($customer, $collector, $validated, $request) {
            $attachmentPath = $request->hasFile('attachment') 
                ? $request->file('attachment')->store('collection_proofs', 'public') 
                : null;

            $collection = Collection::create([
                'customer_id' => $customer->id,
                'collector_id' => $collector->id,
                'amount' => $validated['amount'],
                'payment_type' => $validated['payment_type'],
                'collection_date' => today(),
                'receipt_no' => $validated['receipt_no'],
                'notes' => $validated['notes'],
                'attachment' => $attachmentPath,
                'bank_name' => $validated['bank_name'] ?? null,
                'reference_no' => $validated['reference_no'] ?? null,
                'is_adhoc' => true,
                'status' => 'pending', // Awaiting Approval
            ]);

            return redirect()->route('collector.adhoc')
                ->with('success', 'تم تسجيل طلب التحصيل بنجاح! سيتم تحديث الحساب فور موافقة الإدارة.');
        });
    }

    /**
     * Show ad-hoc visit form.
     */
    public function showAdhocVisitForm(Customer $customer): View
    {
        $collector = auth()->user()->collector;
        if ($customer->collector_id !== $collector->id) { abort(403); }

        $visitTypes = \App\Models\VisitType::orderBy('id')->get();
        return view('collector-portal.adhoc.visit', compact('customer', 'collector', 'visitTypes'));
    }

    /**
     * Store ad-hoc visit (Pending Approval).
     */
    public function storeAdhocVisit(Request $request, Customer $customer): RedirectResponse
    {
        $collector = auth()->user()->collector;
        if ($customer->collector_id !== $collector->id) { abort(403); }

        $validated = $request->validate([
            'visit_type' => 'required|string',
            'notes' => 'nullable|string|max:1000',
            'attachment' => 'nullable|image|max:2048',
        ]);

        return DB::transaction(function () use ($customer, $collector, $validated, $request) {
            $attachmentPath = $request->hasFile('attachment') 
                ? $request->file('attachment')->store('visit_proofs', 'public') 
                : null;

            $visit = \App\Models\Visit::create([
                'collector_id' => $collector->id,
                'customer_id' => $customer->id,
                'visit_type' => $validated['visit_type'],
                'visit_time' => now(),
                'notes' => $validated['notes'],
                'attachment' => $attachmentPath,
                'is_adhoc' => true,
                'status' => 'pending', // Awaiting Approval
            ]);

            return redirect()->route('collector.adhoc')
                ->with('success', 'تم تسجيل طلب الزيارة بنجاح! سيتم مراجعتها من قبل الإدارة.');
        });
    }

    /**
     * Show collector's pending approvals.
     */
    public function myApprovals(): View
    {
        $collector = auth()->user()->collector;
        $pendingCollections = Collection::where('collector_id', $collector->id)
            ->where('status', 'pending')->with('customer')->latest()->get();
        $pendingVisits = \App\Models\Visit::where('collector_id', $collector->id)
            ->where('status', 'pending')->with('customer')->latest()->get();
        $pendingInvoices = \App\Models\SaleInvoice::where('created_by', auth()->id())
            ->where('status', 'pending_approval')->with('customer')->latest()->get();

        return view('collector-portal.approvals', compact('pendingCollections', 'pendingVisits', 'pendingInvoices'));
    }
}
