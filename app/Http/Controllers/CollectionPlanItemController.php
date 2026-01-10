<?php

namespace App\Http\Controllers;

use App\Models\CollectionPlanItem;
use App\Models\CollectionPlan;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionPlanItemController extends Controller
{
    /**
     * Display a listing of collection plan items.
     */
    public function index(): View
    {
        $items = CollectionPlanItem::with('collectionPlan', 'customer')
            ->paginate(15);
        return view('collection-plan-items.index', compact('items'));
    }

    /**
     * Show the form for creating a new collection plan item.
     */
    public function create(): View
    {
        $plans = CollectionPlan::all();
        $customers = Customer::all();
        return view('collection-plan-items.create', compact('plans', 'customers'));
    }

    /**
     * Store a newly created collection plan item in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'collection_plan_id' => 'required|exists:collection_plans,id',
            'customer_id' => 'required|exists:customers,id',
            'expected_amount' => 'required|numeric|min:0.01',
            'priority' => 'required|integer|min:1',
        ]);

        CollectionPlanItem::create($validated);

        return redirect()->route('collection-plan-items.index')
            ->with('success', 'Collection plan item created successfully.');
    }

    /**
     * Display the specified collection plan item.
     */
    public function show(CollectionPlanItem $collectionPlanItem): View
    {
        $collectionPlanItem->load('collectionPlan', 'customer');
        return view('collection-plan-items.show', compact('collectionPlanItem'));
    }

    /**
     * Show the form for editing the specified collection plan item.
     */
    public function edit(CollectionPlanItem $collectionPlanItem): View
    {
        $plans = CollectionPlan::all();
        $customers = Customer::all();
        return view(
            'collection-plan-items.edit',
            compact('collectionPlanItem', 'plans', 'customers')
        );
    }

    /**
     * Update the specified collection plan item in database.
     */
    public function update(Request $request, CollectionPlanItem $collectionPlanItem)
    {
        $validated = $request->validate([
            'collection_plan_id' => 'required|exists:collection_plans,id',
            'customer_id' => 'required|exists:customers,id',
            'expected_amount' => 'required|numeric|min:0.01',
            'priority' => 'required|integer|min:1',
        ]);

        $collectionPlanItem->update($validated);

        return redirect()->route('collection-plan-items.show', $collectionPlanItem)
            ->with('success', 'Collection plan item updated successfully.');
    }

    /**
     * Remove the specified collection plan item from database.
     */
    public function destroy(CollectionPlanItem $collectionPlanItem)
    {
        $collectionPlanItem->delete();

        return redirect()->route('collection-plan-items.index')
            ->with('success', 'Collection plan item deleted successfully.');
    }
}
