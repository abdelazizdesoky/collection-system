namespace App\Console\Commands\Accounting;

use Illuminate\Console\Command;
use App\Models\Installment;
use App\Services\AccountingService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ApplyLatePenalties extends Command
{
    protected $signature = 'erp:apply-late-penalties';
    protected $description = 'Apply compound interest to installments overdue for more than a year.';

    public function handle(AccountingService $accountingService)
    {
        $this->info('Starting penalty application process...');

        // Find pending installments older than 1 year from their due_date
        // AND (they never had a penalty OR their last penalty was more than a year ago)
        $oneYearAgo = Carbon::now()->subYear();

        $installments = Installment::where('status', 'pending')
            ->where('due_date', '<=', $oneYearAgo)
            ->where(function ($query) use ($oneYearAgo) {
                $query->whereNull('last_penalty_date')
                    ->orWhere('last_penalty_date', '<=', $oneYearAgo);
            })
            ->get();

        if ($installments->isEmpty()) {
            $this->info('No eligible installments found for penalties.');
            return;
        }

        $rate = 0.10; // 10% penalty rate (could be move to settings later)

        foreach ($installments as $installment) {
            DB::transaction(function () use ($installment, $accountingService, $rate) {
                $currentAmount = (float) $installment->amount;
                $penalty = $currentAmount * $rate;
                $newAmount = $currentAmount + $penalty;

                // 1. Record in Customer Ledger
                $accountingService->recordCustomerTransaction($installment->installmentPlan->customer_id, [
                    'date' => now(),
                    'description' => "فائدة تأخير مركبة (10%) - قسط مستحق في {$installment->due_date->format('Y-m-d')}",
                    'debit' => $penalty,
                    'credit' => 0,
                    'reference_type' => 'LatePenalty',
                    'reference_id' => $installment->id,
                ]);

                // 2. Update Installment
                $installment->update([
                    'amount' => $newAmount,
                    'penalty_amount' => $installment->penalty_amount + $penalty,
                    'last_penalty_date' => now(),
                    'status' => 'overdue', // Ensure it's marked as overdue
                ]);

                $this->line("Applied {$penalty} penalty to Installment #{$installment->id} (Customer: {$installment->installmentPlan->customer->name})");
            });
        }

        $this->info('Penalty application process completed.');
    }
}
