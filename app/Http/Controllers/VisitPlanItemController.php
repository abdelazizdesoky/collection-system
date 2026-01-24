<?php

namespace App\Http\Controllers;

use App\Models\VisitPlanItem;
use App\Models\VisitPlan;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class VisitPlanItemController extends Controller
{
    /**
     * Store a newly created visit plan item.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'visit_plan_id' => 'required|exists:visit_plans,id',
            'customer_id' => 'required|exists:customers,id',
            'priority' => 'nullable|integer|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        // Check if customer already in plan
        $exists = VisitPlanItem::where('visit_plan_id', $validated['visit_plan_id'])
            ->where('customer_id', $validated['customer_id'])
            ->exists();

        if ($exists) {
            return redirect()->back()
                ->with('error', 'هذا العميل موجود بالفعل في الخطة.');
        }

        // Get max priority and add 1
        if (empty($validated['priority'])) {
            $maxPriority = VisitPlanItem::where('visit_plan_id', $validated['visit_plan_id'])
                ->max('priority') ?? 0;
            $validated['priority'] = $maxPriority + 1;
        }

        $validated['status'] = 'pending';

        VisitPlanItem::create($validated);

        return redirect()->back()
            ->with('success', 'تم إضافة العميل للخطة بنجاح.');
    }

    /**
     * Update the priority of a visit plan item.
     */
    public function updatePriority(Request $request, VisitPlanItem $visitPlanItem): RedirectResponse
    {
        $validated = $request->validate([
            'priority' => 'required|integer|min:0',
        ]);

        $visitPlanItem->update(['priority' => $validated['priority']]);

        return redirect()->back()
            ->with('success', 'تم تحديث ترتيب العميل.');
    }

    /**
     * Update the status of a visit plan item.
     */
    public function updateStatus(Request $request, VisitPlanItem $visitPlanItem): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,visited,skipped',
        ]);

        $visitPlanItem->update(['status' => $validated['status']]);

        // Check if all items are done, update plan status
        $plan = $visitPlanItem->visitPlan;
        $pendingCount = $plan->items()->where('status', 'pending')->count();

        if ($pendingCount === 0) {
            $plan->update(['status' => 'closed']);
        } elseif ($plan->status === 'open') {
            $plan->update(['status' => 'in_progress']);
        }

        return redirect()->back()
            ->with('success', 'تم تحديث حالة العميل.');
    }

    /**
     * Remove the specified visit plan item.
     */
    public function destroy(VisitPlanItem $visitPlanItem): RedirectResponse
    {
        $planId = $visitPlanItem->visit_plan_id;
        $visitPlanItem->delete();

        return redirect()->route('visit-plans.show', $planId)
            ->with('success', 'تم حذف العميل من الخطة.');
    }
}
