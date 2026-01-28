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
    public function edit(InstallmentPlan $plan): View
    {
        return view('installments.edit', compact('plan'));
    }

    public function update(Request $request, InstallmentPlan $plan)
{
    $validated = $request->validate([
        'invoice_no' => 'nullable|string|max:50',
        'notes' => 'nullable|string|max:1000',
        'status' => 'required|string|max:50',
    ]);

    $plan->update($validated);

    // تحديث وصف الحساب إذا تغير رقم الفاتورة
    if ($plan->wasChanged('invoice_no')) {
        CustomerAccount::where('reference_type', 'InstallmentPlan')
            ->where('reference_id', $plan->id)
            ->update([
                'description' => "نظام تقسيط - فاتورة رقم {$validated['invoice_no']} - مدة {$plan->duration_months} شهر",
            ]);
    }

    return redirect()->route('installments.show', $plan)
        ->with('success', 'تم تحديث بيانات الخطة بنجاح.');
}
 

    public function destroy(InstallmentPlan $plan, \App\Services\AccountBalanceService $accountService)
    {
        DB::transaction(function () use ($plan, $accountService) {
            // 1. Cancel the Ledger Entry for the Plan (The Financed Amount Debit)
            $accountService->cancelTransaction('InstallmentPlan', $plan->id);

            // 2. Soft delete installments first
            $plan->installments()->delete();
            
            // 3. Soft delete plan
            $plan->delete();
        });

        return redirect()->route('installments.index')
            ->with('success', 'تم حذف خطة التقسيط وإلغاء القيد المالي بنجاح.');
    }

    public function printReceipts(InstallmentPlan $plan): View
    {
        $plan->load(['customer', 'installments' => function($q) {
            $q->orderBy('due_date');
        }]);

        return view('installments.print', compact('plan'));
    }

    // --- Item Management ---

    public function editItem(Installment $installment): View
    {
        return view('installments.edit-item', compact('installment'));
    }

    public function updateItem(Request $request, Installment $installment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'due_date' => 'required|date',
            'status' => 'required|string|max:50',
        ]);

        return DB::transaction(function () use ($validated, $installment) {
            $oldAmount = (float) $installment->amount;
            $installment->update($validated);

            if ($oldAmount != (float) $installment->amount) {
                $diff = (float) $installment->amount - $oldAmount;
                // If installment increased (diff > 0), customer DEBT increases -> DEBIT.
                // If installment decreased (diff < 0), customer DEBT decreases -> CREDIT.
                
                CustomerAccount::create([
                    'customer_id' => $installment->installmentPlan->customer_id,
                    'date' => now(),
                    'description' => "تعديل قيمة قسط - خطة #{$installment->installmentPlan->id}",
                    'debit' => $diff > 0 ? $diff : 0,
                    'credit' => $diff < 0 ? abs($diff) : 0,
                    'balance' => 0, // Recalculated
                    'reference_type' => 'InstallmentAdjustment',
                    'reference_id' => $installment->id,
                ]);

                app(\App\Services\AccountBalanceService::class)->recalculateBalance($installment->installmentPlan->customer_id);
            }

            return redirect()->route('installments.show', $installment->installmentPlan)
                ->with('success', 'تم تحديث بيانات القسط وتعديل الحساب بنجاح.');
        });
    }

    public function destroyItem(Installment $installment)
    {
        $plan = $installment->installmentPlan;
        
        return DB::transaction(function () use ($installment, $plan) {
            // Delete the installment debt from customer ledger
            CustomerAccount::create([
                'customer_id' => $plan->customer_id,
                'date' => now(),
                'description' => "حذف قسط - قيمة " . number_format($installment->amount, 2),
                'debit' => 0,
                'credit' => $installment->amount,
                'balance' => 0, // Recalculated
                'reference_type' => 'InstallmentDeletion',
                'reference_id' => $installment->id,
            ]);

            $installment->delete();

            app(\App\Services\AccountBalanceService::class)->recalculateBalance($plan->customer_id);

            return redirect()->route('installments.show', $plan)
                ->with('success', 'تم حذف القسط وتعديل رصيد العميل بنجاح.');
        });
    }
}
