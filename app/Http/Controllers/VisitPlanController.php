<?php

namespace App\Http\Controllers;

use App\Models\VisitPlan;
use App\Models\VisitPlanItem;
use App\Models\Collector;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class VisitPlanController extends Controller
{
    /**
     * Display a listing of visit plans.
     */
    public function index(Request $request): View
    {
        $query = VisitPlan::with(['collector', 'createdBy', 'items'])
            ->latest();

        // Filter by collector
        if ($request->filled('collector_id')) {
            $query->where('collector_id', $request->collector_id);
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by frequency
        if ($request->filled('frequency')) {
            $query->where('frequency', $request->frequency);
        }

        // Filter by date range
        if ($request->filled('start_date')) {
            $query->where('start_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->where('start_date', '<=', $request->end_date);
        }

        $plans = $query->paginate(15)->withQueryString();
        $collectors = Collector::all();

        return view('visit-plans.index', compact('plans', 'collectors'));
    }

    /**
     * Show the form for creating a new visit plan.
     */
    public function create(): View
    {
        $collectors = Collector::all();
        // Determine if we should filter customers by selected collector (AJAX would be better)
        // For now, load all, but in view we can filter via JS or reload
        // Or if we want strict: passing collector_id or just load all for admin
        
        $customers = Customer::orderBy('name')->get();

        return view('visit-plans.create', compact('collectors', 'customers'));
    }

    /**
     * Get customers for a specific collector (API/AJAX endpoint).
     */
    public function getCustomers($collectorId)
    {
        $customers = Customer::where('collector_id', $collectorId)
            ->withCount(['installments as due_installments_count' => function($query) {
                $query->where('status', 'pending')
                      ->whereDate('due_date', '<=', now());
            }])
            ->select('id', 'name', 'phone', 'address')
            ->orderBy('name')
            ->get();
            
        return response()->json($customers);
    }

    /**
     * Store a newly created visit plan in database.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'collector_id' => 'required|exists:collectors,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'required|in:daily,weekly,monthly',
            'notes' => 'nullable|string|max:1000',
            'customers' => 'nullable|array',
            'customers.*' => 'exists:customers,id',
            'priorities' => 'nullable|array',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['status'] = 'open';

        // For daily plans, end_date equals start_date
        if ($validated['frequency'] === 'daily') {
            $validated['end_date'] = $validated['start_date'];
        }

        $plan = VisitPlan::create($validated);

        // Add customers to the plan
        if (!empty($validated['customers'])) {
            foreach ($validated['customers'] as $customerId) {
                VisitPlanItem::create([
                    'visit_plan_id' => $plan->id,
                    'customer_id' => $customerId,
                    'priority' => $request->input("priority_{$customerId}", 0),
                    'status' => 'pending',
                ]);
            }
        }

        return redirect()->route('visit-plans.show', $plan)
            ->with('success', 'تم إنشاء خطة الزيارات بنجاح.');
    }

    /**
     * Display the specified visit plan.
     */
    public function show(VisitPlan $visitPlan): View
    {
        $visitPlan->load([
            'collector',
            'createdBy',
            'items' => function ($query) {
                $query->orderBy('priority')->with(['customer', 'visit']);
            }
        ]);

        // Filter customers: strictly those belonging to the plan's collector, excluding those already in the plan
        $customers = Customer::where('collector_id', $visitPlan->collector_id)
            ->whereNotIn('id', $visitPlan->items->pluck('customer_id'))
            ->orderBy('name')
            ->get();

        return view('visit-plans.show', compact('visitPlan', 'customers'));
    }

    /**
     * Show the form for editing the specified visit plan.
     */
    public function edit(VisitPlan $visitPlan): View
    {
        $collectors = Collector::all();
        // Although the current edit view doesn't list customers, we filter them for consistency
        // in case the view is updated to include customer management.
        $customers = Customer::where('collector_id', $visitPlan->collector_id)
            ->orderBy('name')
            ->get();

        return view('visit-plans.edit', compact('visitPlan', 'collectors', 'customers'));
    }

    /**
     * Update the specified visit plan in database.
     */
    public function update(Request $request, VisitPlan $visitPlan): RedirectResponse
    {
        $validated = $request->validate([
            'collector_id' => 'required|exists:collectors,id',
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'frequency' => 'required|in:daily,weekly,monthly',
            'status' => 'required|in:open,in_progress,closed',
            'notes' => 'nullable|string|max:1000',
        ]);

        // For daily plans, end_date equals start_date
        if ($validated['frequency'] === 'daily') {
            $validated['end_date'] = $validated['start_date'];
        }

        $visitPlan->update($validated);

        return redirect()->route('visit-plans.show', $visitPlan)
            ->with('success', 'تم تحديث خطة الزيارات بنجاح.');
    }

    /**
     * Remove the specified visit plan from database.
     */
    public function destroy(VisitPlan $visitPlan): RedirectResponse
    {
        $visitPlan->delete();

        return redirect()->route('visit-plans.index')
            ->with('success', 'تم حذف خطة الزيارات بنجاح.');
    }

    /**
     * Show details for a specific visit.
     */
    public function showVisitDetails(\App\Models\Visit $visit): View
    {
        $visit->load(['customer', 'collection', 'visitPlanItem.visitPlan']);

        // Determine which dashboard to show in breadcrumbs/back link based on role
        $isCollector = auth()->user()->hasRole('collector');

        return view('collector-portal.visit-details', compact('visit', 'isCollector'));
    }
}
