<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(): View
    {
        $customers = Customer::paginate(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Show the form for creating a new customer.
     */
    public function create(): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }
        return view('customers.create');
    }

    /**
     * Store a newly created customer in database.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'opening_balance' => 'required|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
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
        $customer->load('collections', 'cheques', 'accounts');
        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer.
     */
    public function edit(Customer $customer): View
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }

        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified customer in database.
     */
    public function update(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasAnyRole(['admin', 'user'])) {
            abort(403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'opening_balance' => 'required|numeric|min:0',
            'balance_type' => 'required|in:debit,credit',
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
        if (!auth()->user()->hasRole('admin')) {
            abort(403);
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
