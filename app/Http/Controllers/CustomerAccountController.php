<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAccount;
use Illuminate\View\View;

class CustomerAccountController extends Controller
{
    /**
     * Display a listing of customer accounts.
     */
    public function index(): View
    {
        $accounts = CustomerAccount::with('customer')
            ->latest()
            ->paginate(15);

        return view('customer-accounts.index', compact('accounts'));
    }

    /**
     * Display ledger for a specific customer.
     */
    public function customerLedger(Customer $customer): View
    {
        $accounts = $customer->accounts()
            ->latest('date')
            ->paginate(20);

        return view('customer-accounts.ledger', compact('customer', 'accounts'));
    }

    /**
     * Show the form for creating a new manual account entry.
     */
    public function create(Customer $customer): View
    {
        return view('customer-accounts.create', compact('customer'));
    }

    /**
     * Store a newly created manual account entry in database.
     */
    public function store(\Illuminate\Http\Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
        ]);

        $amount = (float) $validated['amount'];
        $isDebit = $validated['type'] === 'debit';

        // Get last balance
        $lastAccount = CustomerAccount::where('customer_id', $customer->id)
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->first();

        $previousBalance = 0;
        if ($lastAccount) {
            $previousBalance = (float) $lastAccount->balance;
        } else {
            $previousBalance = (float) ($customer->opening_balance ?? 0);
        }

        $newBalance = $isDebit ? ($previousBalance + $amount) : ($previousBalance - $amount);

        CustomerAccount::create([
            'customer_id' => $customer->id,
            'date' => $validated['date'],
            'description' => $validated['description'],
            'debit' => $isDebit ? $amount : 0,
            'credit' => $isDebit ? 0 : $amount,
            'balance' => $newBalance,
            'reference_type' => 'Manual',
            'reference_id' => null,
        ]);

        return redirect()->route('customer.ledger', $customer)
            ->with('success', 'تم تسجيل العملية المالية بنجاح.');
    }

    /**
     * Display the specified customer account entry.
     */
    public function show(CustomerAccount $customerAccount): View
    {
        $customerAccount->load('customer');

        return view('customer-accounts.show', compact('customerAccount'));
    }
}
