<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Exports\CustomersExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $showTrashed = $request->input('trashed') === '1';

        $query = Customer::query();

        if ($showTrashed) {
            $query->onlyTrashed();
        }

        $customers = $query
            ->when($search, function($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                             ->orWhere('phone', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $trashedCount = Customer::onlyTrashed()->count();
        $activeCount = Customer::count();

        return view('customers.index', compact('customers', 'showTrashed', 'trashedCount', 'activeCount'));
    }

    /**
     * Restore a soft deleted customer.
     */
    public function restore($id)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->restore();

        return redirect()->route('customers.index', ['trashed' => '1'])
            ->with('success', 'تم استعادة العميل بنجاح.');
    }

    /**
     * Permanently delete a customer.
     */
    public function forceDelete($id)
    {
        if (! auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $customer = Customer::onlyTrashed()->findOrFail($id);
        $customer->forceDelete();

        return redirect()->route('customers.index', ['trashed' => '1'])
            ->with('success', 'تم حذف العميل نهائياً.');
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $areas = \App\Models\Area::orderBy('name')->get();
        $collectors = \App\Models\Collector::all();

        return view('customers.create', compact('areas', 'collectors'));
    }

    /**
     * Store a newly created customer in database.
     */
    public function store(Request $request)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'code' => 'nullable|string|unique:customers,code',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'opening_balance' => 'required|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
            'area_id' => 'nullable|exists:areas,id',
            'collector_id' => 'nullable|exists:collectors,id',
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    /**
     * Display the specified customer.
     */
    public function show(Customer $customer): View
    {
        $customer->load('collections', 'cheques', 'accounts', 'area', 'collector');

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }

        $areas = \App\Models\Area::orderBy('name')->get();
        $collectors = \App\Models\Collector::all();

        return view('customers.edit', compact('customer', 'areas', 'collectors'));
    }

    /**
     * Update the specified customer in database.
     */
    public function update(Request $request, Customer $customer)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'code' => 'nullable|string|unique:customers,code,' . $customer->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'opening_balance' => 'required|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
            'area_id' => 'nullable|exists:areas,id',
            'collector_id' => 'nullable|exists:collectors,id',
        ]);

        $customer->update($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer from database.
     */
    public function destroy(Customer $customer)
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }

    /**
     * Export customers to Excel.
     */
    public function export()
    {
        if (! auth()->user()->hasAnyRole(['admin', 'supervisor'])) {
            abort(403);
        }

        return Excel::download(new CustomersExport, 'customers_'.now()->format('Y-m-d_His').'.xlsx');
    }
}
