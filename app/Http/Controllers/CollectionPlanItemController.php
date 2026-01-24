<?php

namespace App\Http\Controllers;

use App\Models\CollectionPlan;
use App\Models\CollectionPlanItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionPlanItemController extends Controller
    /**
     * Show the form for creating a new collection plan item (single).
     */
    public function create(Request $request): View
    {
        $planId = $request->query('collection_plan_id');
        $collectionPlan = CollectionPlan::findOrFail($planId);
        
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        // Allow selection from customers belonging to the plan's collector
        // Excluding those already in the plan
        $customers = Customer::where('collector_id', $collectionPlan->collector_id)
            ->whereNotIn('id', $collectionPlan->items->pluck('customer_id'))
            ->orderBy('name')
            ->get();

        return view('collection-plan-items.create', compact('collectionPlan', 'customers'));
    }

    /**
     * Store a newly created collection plan item in database.
     */
    public function store(Request $request): RedirectResponse
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $validated = $request->validate([
            'collection_plan_id' => 'required|exists:collection_plans,id',
            'customer_id' => 'required|exists:customers,id',
            'expected_amount' => 'nullable|numeric|min:0',
            'priority' => 'nullable|integer|min:0',
        ]);

        // Default priority if not provided
        if (!isset($validated['priority'])) {
            $validated['priority'] = 0;
        }

        $validated['status'] = 'pending';

        CollectionPlanItem::create($validated);

        return redirect()->route('collection-plans.show', $validated['collection_plan_id'])
            ->with('success', 'Customer added to collection plan successfully.');
    }

    /**
     * Show the form for editing the specified collection plan item.
     */
    public function edit(CollectionPlanItem $collectionPlanItem): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        // Ideally we shouldn't change customer of an item, but maybe amounts/priority.
        // If we want to change customer, filtering logic applies again.
        
        return view('collection-plan-items.edit', compact('collectionPlanItem'));
    }

    /**
     * Update the specified collection plan item in database.
     */
    public function update(Request $request, CollectionPlanItem $collectionPlanItem): RedirectResponse
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $validated = $request->validate([
            'expected_amount' => 'nullable|numeric|min:0',
            'priority' => 'nullable|integer|min:0',
        ]);

        $collectionPlanItem->update($validated);

        return redirect()->route('collection-plans.show', $collectionPlanItem->collection_plan_id)
            ->with('success', 'Collection plan item updated successfully.');
    }

    /**
     * Remove the specified collection plan item from database.
     */
    public function destroy(CollectionPlanItem $collectionPlanItem): RedirectResponse
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $planId = $collectionPlanItem->collection_plan_id;
        $collectionPlanItem->delete();

        return redirect()->route('collection-plans.show', $planId)
            ->with('success', 'Collection plan item deleted successfully.');
    }
}
