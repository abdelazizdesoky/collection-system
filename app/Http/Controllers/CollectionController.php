<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Customer;
use App\Models\Collector;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index(): View
    {
        $collections = Collection::with('customer', 'collector')
            ->paginate(15);
        return view('collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create(): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'collector'])) {
            abort(403);
        }

        $customers = Customer::all();
        $collectors = Collector::all();
        return view('collections.create', compact('customers', 'collectors'));
    }

    /**
     * Store a newly created collection in database.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'collector'])) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'collector_id' => 'required|exists:collectors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:cash,cheque',
            'collection_date' => 'required|date',
            'receipt_no' => 'required|string|unique:collections,receipt_no',
            'notes' => 'nullable|string',
        ]);

        Collection::create($validated);

        return redirect()->route('collections.index')
            ->with('success', 'Collection recorded successfully.');
    }

    /**
     * Display the specified collection.
     */
    public function show(Collection $collection): View
    {
        $collection->load('customer', 'collector', 'accountEntry');
        return view('collections.show', compact('collection'));
    }

    /**
     * Show the form for editing the specified collection.
     */
    public function edit(Collection $collection): View
    {
        // Only admins can edit existing collections
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $customers = Customer::all();
        $collectors = Collector::all();
        return view('collections.edit', compact('collection', 'customers', 'collectors'));
    }

    /**
     * Update the specified collection in database.
     */
    public function update(Request $request, Collection $collection)
    {
        // Only admins can update collections
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'collector_id' => 'required|exists:collectors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:cash,cheque',
            'collection_date' => 'required|date',
            'receipt_no' => 'required|string|unique:collections,receipt_no,' . $collection->id,
            'notes' => 'nullable|string',
        ]);

        $collection->update($validated);

        return redirect()->route('collections.show', $collection)
            ->with('success', 'Collection updated successfully.');
    }

    /**
     * Remove the specified collection from database.
     */
    public function destroy(Collection $collection)
    {
        // Only admins can delete collections
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $collection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted successfully.');
    }
}
