<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Supplier;
use App\Services\AccountingService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccountAdjustmentController extends Controller
{
    public function __construct(protected AccountingService $accountingService) {}

    /**
     * Show form to adjust customer account.
     */
    public function createCustomer(Customer $customer): View
    {
        return view('accounting.adjustments.customer', compact('customer'));
    }

    /**
     * Store customer adjustment.
     */
    public function storeCustomer(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $this->accountingService->recordCustomerTransaction($customer->id, [
            'date' => $validated['date'],
            'description' => $validated['description'],
            'debit' => $validated['type'] === 'debit' ? $validated['amount'] : 0,
            'credit' => $validated['type'] === 'credit' ? $validated['amount'] : 0,
            'reference_type' => 'ManualAdjustment',
        ]);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'تم تسجيل التسوية الحسابية للعميل بنجاح.');
    }

    /**
     * Show form to adjust supplier account.
     */
    public function createSupplier(Supplier $supplier): View
    {
        return view('accounting.adjustments.supplier', compact('supplier'));
    }

    /**
     * Store supplier adjustment.
     */
    public function storeSupplier(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:debit,credit',
            'amount' => 'required|numeric|min:0.01',
            'description' => 'required|string|max:255',
        ]);

        $this->accountingService->recordSupplierTransaction($supplier->id, [
            'date' => $validated['date'],
            'description' => $validated['description'],
            'debit' => $validated['type'] === 'debit' ? $validated['amount'] : 0,
            'credit' => $validated['type'] === 'credit' ? $validated['amount'] : 0,
            'reference_type' => 'ManualAdjustment',
        ]);

        return redirect()->route('suppliers.show', $supplier)
            ->with('success', 'تم تسجيل التسوية الحسابية للمورد بنجاح.');
    }
}
