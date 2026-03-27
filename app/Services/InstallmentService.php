<?php

namespace App\Services;

use App\Models\Installment;
use App\Models\InstallmentPlan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InstallmentService
{
    public function __construct(protected AccountingService $accountingService) {}

    /**
     * Create an installment plan and its installments.
     */
    public function createPlan(array $data): InstallmentPlan
    {
        return DB::transaction(function () use ($data) {
            // 1. Calculate financing
            $totalAmount = (float) $data['total_amount'];
            $downPayment = (float) ($data['down_payment'] ?? 0);
            $increasePercentage = (float) ($data['increase_percentage'] ?? 0);
            $durationMonths = (int) $data['duration_months'];

            $remainingAfterDown = $totalAmount - $downPayment;
            $increaseAmount = $remainingAfterDown * ($increasePercentage / 100);
            $financedAmount = $remainingAfterDown + $increaseAmount;
            $monthlyAmount = $durationMonths > 0 ? ($financedAmount / $durationMonths) : $financedAmount;

            // 2. Create Plan
            $plan = InstallmentPlan::create([
                'customer_id' => $data['customer_id'],
                'invoice_no' => $data['invoice_no'] ?? null,
                'total_amount' => $totalAmount,
                'down_payment' => $downPayment,
                'increase_percentage' => $increasePercentage,
                'financed_amount' => $financedAmount,
                'duration_months' => $durationMonths,
                'monthly_amount' => $monthlyAmount,
                'start_date' => $data['start_date'] ?? now(),
                'notes' => $data['notes'] ?? null,
                'status' => 'active',
            ]);

            // 3. Create Monthly Installments
            $startDate = Carbon::parse($data['start_date'] ?? now());
            for ($i = 0; $i < $durationMonths; $i++) {
                Installment::create([
                    'installment_plan_id' => $plan->id,
                    'due_date' => $startDate->copy()->addMonths($i),
                    'amount' => $monthlyAmount,
                    'status' => 'pending',
                ]);
            }

            // 4. Update Customer Ledger via AccountingService (Debit FINANCED amount)
            $this->accountingService->recordCustomerTransaction($data['customer_id'], [
                'date' => now(),
                'description' => 'نظام تقسيط - فاتورة رقم '.($data['invoice_no'] ?? $plan->id)." - مدة {$durationMonths} شهر",
                'debit' => $financedAmount,
                'credit' => 0,
                'reference_type' => 'InstallmentPlan',
                'reference_id' => $plan->id,
            ]);

            return $plan;
        });
    }
}
