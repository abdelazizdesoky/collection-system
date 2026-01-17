<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\Collector;
use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Exports\CollectionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CollectionController extends Controller
{
    /**
     * Display a listing of collections.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $collections = Collection::with('customer', 'collector')
            ->when($search, function($query, $search) {
                return $query->where('receipt_no', 'like', "%{$search}%")
                    ->orWhereHas('customer', function($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('collector', function($q) use ($search) {
                        $q->whereHas('user', function($qu) use ($search) {
                            $qu->where('name', 'like', "%{$search}%");
                        });
                    });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('collections.index', compact('collections'));
    }

    /**
     * Show the form for creating a new collection.
     */
    public function create(): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'collector'])) {
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
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'collector'])) {
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

        return DB::transaction(function () use ($validated) {
            $collection = Collection::create($validated);

            // Update Customer Account (Ledger) Logic
            $lastAccount = CustomerAccount::where('customer_id', $validated['customer_id'])
                ->orderBy('id', 'desc')
                ->first();

            $previousBalance = 0;
            if ($lastAccount) {
                $previousBalance = $lastAccount->balance;
            } else {
                $customer = Customer::find($validated['customer_id']);
                $previousBalance = $customer->opening_balance ?? 0;
            }

            $newBalance = $previousBalance - $validated['amount'];

            CustomerAccount::create([
                'customer_id' => $validated['customer_id'],
                'date' => $validated['collection_date'],
                'description' => "Payment received - Receipt #{$validated['receipt_no']}",
                'debit' => 0,
                'credit' => $validated['amount'],
                'balance' => $newBalance,
                'reference_type' => 'Collection',
                'reference_id' => $collection->id,
            ]);

            return redirect()->route('collections.index')
                ->with('success', 'Collection recorded and balance updated successfully.');
        });
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
        // Admins and Supervisor can edit existing collections
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
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
        // Admins and Supervisor can update collections
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'collector_id' => 'required|exists:collectors,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_type' => 'required|in:cash,cheque',
            'collection_date' => 'required|date',
            'receipt_no' => 'required|string|unique:collections,receipt_no,'.$collection->id,
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
        // Admins and Supervisor can delete collections
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $collection->delete();

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted successfully.');
    }

    /**
     * Export collections to Excel.
     */
    public function export()
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        return Excel::download(new CollectionsExport, 'collections_'.now()->format('Y-m-d_His').'.xlsx');
    }
}
