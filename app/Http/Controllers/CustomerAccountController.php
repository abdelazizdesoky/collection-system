<?php

namespace App\Http\Controllers;

use App\Models\CustomerAccount;
use App\Models\Customer;
use Illuminate\Http\Request;
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
     * Display the specified customer account entry.
     */
    public function show(CustomerAccount $customerAccount): View
    {
        $customerAccount->load('customer');
        return view('customer-accounts.show', compact('customerAccount'));
    }
}
