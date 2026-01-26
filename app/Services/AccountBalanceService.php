<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerAccount;
use Illuminate\Support\Facades\DB;

class AccountBalanceService
{
    /**
     * Cancel a transaction in the ledger and recalculate balance.
     */
    public function cancelTransaction(string $type, int $id): void
    {
        $entries = CustomerAccount::where('reference_type', $type)
            ->where('reference_id', $id)
            ->get();

        foreach ($entries as $entry) {
            // Mark as cancelled
            $entry->update([
                'status' => 'cancelled',
                'description' => 'ملغي - ' . $entry->description
            ]);
            
            // Recalculate ledger for this customer
            $this->recalculateBalance($entry->customer_id);
        }
    }

    /**
     * Recalculate running balance for a customer based on active entries.
     */
    public function recalculateBalance(int $customerId): void
    {
        $customer = Customer::find($customerId);
        if (!$customer) return;

        $runningBalance = $customer->opening_balance ?? 0;

        // Process active entries in chronological order
        // We assume ID order is sufficient for chronological order in this system
        $entries = CustomerAccount::where('customer_id', $customerId)
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        foreach ($entries as $entry) {
            // Add Debit, Subtract Credit
            $runningBalance += ($entry->debit - $entry->credit);
            
            // Update balance column
            if (abs($entry->balance - $runningBalance) > 0.001) { // Float comparison safe
                $entry->balance = $runningBalance;
                $entry->saveQuietly();
            }
        }
    }
}
