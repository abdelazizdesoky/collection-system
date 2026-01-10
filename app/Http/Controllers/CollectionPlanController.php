<?php

namespace App\Http\Controllers;

use App\Models\CollectionPlan;
use App\Models\Collector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionPlanController extends Controller
{
    /**
     * Display a listing of collection plans.
     */
    public function index(): View
    {
        $plans = CollectionPlan::with('collector', 'items')
            ->paginate(15);
        return view('collection-plans.index', compact('plans'));
    }

    /**
     * Show the form for creating a new collection plan.
     */
    public function create(): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        $collectors = Collector::all();
        return view('collection-plans.create', compact('collectors'));
    }

    /**
     * Store a newly created collection plan in database.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        $validated = $request->validate([
            'collector_id' => 'required|exists:collectors,id',
            'name' => 'required|string|max:255',
            'plan_date' => 'required|date',
            'collection_type' => 'required|in:regular,special',
            'type' => 'nullable|string|max:255',
        ]);

        // Rename plan_date to date for database storage
        $validated['date'] = $validated['plan_date'];
        unset($validated['plan_date']);

        CollectionPlan::create($validated);

        return redirect()->route('collection-plans.index')
            ->with('success', 'Collection plan created successfully.');
    }

    /**
     * Display the specified collection plan.
     */
    public function show(CollectionPlan $collectionPlan): View
    {
        $collectionPlan->load('collector', 'items.customer');
        return view('collection-plans.show', compact('collectionPlan'));
    }

    /**
     * Show the form for editing the specified collection plan.
     */
    public function edit(CollectionPlan $collectionPlan): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        $collectors = Collector::all();
        return view('collection-plans.edit', compact('collectionPlan', 'collectors'));
    }

    /**
     * Update the specified collection plan in database.
     */
    public function update(Request $request, CollectionPlan $collectionPlan)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        $validated = $request->validate([
            'collector_id' => 'required|exists:collectors,id',
            'name' => 'required|string|max:255',
            'plan_date' => 'required|date',
            'collection_type' => 'required|in:regular,special',
            'type' => 'nullable|string|max:255',
        ]);

        // Rename plan_date to date for database storage
        $validated['date'] = $validated['plan_date'];
        unset($validated['plan_date']);

        $collectionPlan->update($validated);

        return redirect()->route('collection-plans.show', $collectionPlan)
            ->with('success', 'Collection plan updated successfully.');
    }

    /**
     * Remove the specified collection plan from database.
     */
    public function destroy(CollectionPlan $collectionPlan)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        $collectionPlan->delete();

        return redirect()->route('collection-plans.index')
            ->with('success', 'Collection plan deleted successfully.');
    }
}
