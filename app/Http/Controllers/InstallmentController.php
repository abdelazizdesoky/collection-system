<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\Installment;
use App\Models\InstallmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class InstallmentController extends Controller
{
    public function index(): View
    {
        $plans = InstallmentPlan::with('customer')
            ->withCount('installments')
            ->latest()
            ->paginate(15);

        return view('installments.index', compact('plans'));
    }

    public function create(): View
    {
        $customers = Customer::orderBy('name')->get();
        return view('installments.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'invoice_no' => 'nullable|string|max:50',
            'total_amount' => 'required|numeric|min:0.01',
            'down_payment' => 'required|numeric|min:0',
            'increase_percentage' => 'required|numeric|min:0',
            'duration_months' => 'required|integer|min:1',
            'start_date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
        ]);

        return DB::transaction(function () use ($validated) {
            // 1. Calculate financing
            $remainingAfterDown = (float) $validated['total_amount'] - (float) $validated['down_payment'];
            $increaseAmount = $remainingAfterDown * ((float) $validated['increase_percentage'] / 100);
            $financedAmount = $remainingAfterDown + $increaseAmount;
            $monthlyAmount = $financedAmount / (int) $validated['duration_months'];

            // 2. Create Plan
            $plan = InstallmentPlan::create([
                'customer_id' => $validated['customer_id'],
                'invoice_no' => $validated['invoice_no'],
                'total_amount' => $validated['total_amount'],
                'down_payment' => $validated['down_payment'],
                'increase_percentage' => $validated['increase_percentage'],
                'financed_amount' => $financedAmount,
                'duration_months' => $validated['duration_months'],
                'monthly_amount' => $monthlyAmount,
                'start_date' => $validated['start_date'],
                'notes' => $validated['notes'],
                'status' => 'active',
            ]);

            // 3. Create Monthly Installments
            $startDate = \Carbon\Carbon::parse($validated['start_date']);
            for ($i = 0; $i < (int) $validated['duration_months']; $i++) {
                Installment::create([
                    'installment_plan_id' => $plan->id,
                    'due_date' => $startDate->copy()->addMonths($i),
                    'amount' => $monthlyAmount,
                    'status' => 'pending',
                ]);
            }

            // 4. Update Customer Ledger (Debit FINANCED amount)
            $lastAccount = CustomerAccount::where('customer_id', $validated['customer_id'])
                ->orderBy('id', 'desc')
                ->first();

            $customer = Customer::find($validated['customer_id']);
            $previousBalance = $lastAccount ? $lastAccount->balance : $customer->opening_balance;
            $newBalance = $previousBalance + $financedAmount;

            CustomerAccount::create([
                'customer_id' => $validated['customer_id'],
                'date' => now(),
                'description' => "نظام تقسيط - فاتورة رقم {$validated['invoice_no']} - مدة {$validated['duration_months']} شهر",
                'debit' => $financedAmount,
                'credit' => 0,
                'balance' => $newBalance,
                'reference_type' => 'InstallmentPlan',
                'reference_id' => $plan->id,
            ]);

            return redirect()->route('installments.show', $plan)
                ->with('success', 'تم إنشاء خطط التقسيط وتحديث حساب العميل بنجاح.');
        });
    }

    public function show(InstallmentPlan $plan): View
    {
        $plan->load(['customer', 'installments' => function($q) {
            $q->orderBy('due_date');
        }]);

        return view('installments.show', compact('plan'));
    }
}
