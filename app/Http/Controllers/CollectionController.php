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
        $showTrashed = $request->input('trashed') === '1';

        $query = Collection::with('customer', 'collector');

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $collections = $query
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

        $trashedCount = Collection::onlyTrashed()->count();
        $activeCount = Collection::count();

        return view('collections.index', compact('collections', 'showTrashed', 'trashedCount', 'activeCount'));
    }

    /**
     * Restore a soft deleted collection.
     */
    public function restore($id)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $collection = Collection::onlyTrashed()->findOrFail($id);
        $collection->restore();

        return redirect()->route('collections.index', ['trashed' => '1'])
            ->with('success', 'تم استعادة التحصيل بنجاح.');
    }

    /**
     * Permanently delete a collection.
     */
    public function forceDelete($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $collection = Collection::onlyTrashed()->findOrFail($id);
        $collection->forceDelete();

        return redirect()->route('collections.index', ['trashed' => '1'])
            ->with('success', 'تم حذف التحصيل نهائياً.');
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
        $banks = \App\Models\Bank::orderBy('name')->get();

        return view('collections.create', compact('customers', 'collectors', 'banks'));
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
        $banks = \App\Models\Bank::orderBy('name')->get();

        return view('collections.edit', compact('collection', 'customers', 'collectors', 'banks'));
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

        return DB::transaction(function () use ($validated, $collection) {
            $oldAmount = $collection->amount;
            $collection->update($validated);

            if ($oldAmount != $collection->amount) {
                // If amount changed, we need to adjust the ledger.
                // Instead of editing the original entry (which might be in the middle of a ledger),
                // we add a correction entry if preferred, OR we update the original and recalculate.
                // The user asked for "مدين بقيمة المبلغ الذى تم زيادة او نقصان" (Debit with the amount increased or decreased).
                
                $diff = $collection->amount - $oldAmount;
                // If diff > 0 (amount increased), we need to CREDIT the customer MORE (reduce balance).
                // If diff < 0 (amount decreased), we need to DEBIT the customer (increase balance).
                
                CustomerAccount::create([
                    'customer_id' => $collection->customer_id,
                    'date' => now(),
                    'description' => "تعديل مبلغ التحصيل - إيصال #{$collection->receipt_no}",
                    'debit' => $diff < 0 ? abs($diff) : 0,
                    'credit' => $diff > 0 ? $diff : 0,
                    'balance' => 0, // Will be recalculated
                    'reference_type' => 'CollectionAdjustment',
                    'reference_id' => $collection->id,
                ]);

                // Recalculate balance for this customer
                $accountService = app(\App\Services\AccountBalanceService::class);
                $accountService->recalculateBalance($collection->customer_id);
            }

            return redirect()->route('collections.show', $collection)
                ->with('success', 'تم تحديث التحصيل وتعديل الرصيد بنجاح.');
        });
    }

    /**
     * Remove the specified collection from database.
     */
    public function destroy(Collection $collection, \App\Services\AccountBalanceService $accountService)
    {
        // Admins and Supervisor can delete collections
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        DB::transaction(function () use ($collection, $accountService) {
            // Cancel ledger entry
            $accountService->cancelTransaction('Collection', $collection->id);
            
            // Soft delete collection
            $collection->delete();
        });

        return redirect()->route('collections.index')
            ->with('success', 'Collection deleted and removed from account balance successfully.');
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
