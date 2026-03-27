<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\CustomerAccount;
use App\Models\Supplier;
use App\Models\SupplierAccount;
use Illuminate\Support\Facades\DB;

class AccountingService
{
    /**
     * Record a transaction for a customer (Debit or Credit).
     */
    public function recordCustomerTransaction(int $customerId, array $data): CustomerAccount
    {
        return DB::transaction(function () use ($customerId, $data) {
            $customer = Customer::findOrFail($customerId);

            // Get last balance
            $lastEntry = CustomerAccount::where('customer_id', $customerId)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            $previousBalance = $lastEntry ? $lastEntry->balance : $customer->opening_balance;

            // For Customer (Asset): Balance = Prev + Debit - Credit
            $debit = (float) ($data['debit'] ?? 0);
            $credit = (float) ($data['credit'] ?? 0);
            $newBalance = $previousBalance + $debit - $credit;

            return CustomerAccount::create([
                'customer_id' => $customerId,
                'date' => $data['date'] ?? now(),
                'description' => $data['description'],
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $newBalance,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'status' => 'active',
            ]);
        });
    }

    /**
     * Record a transaction for a supplier (Debit or Credit).
     */
    public function recordSupplierTransaction(int $supplierId, array $data): SupplierAccount
    {
        return DB::transaction(function () use ($supplierId, $data) {
            $supplier = Supplier::findOrFail($supplierId);

            // Get last balance
            $lastEntry = SupplierAccount::where('supplier_id', $supplierId)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            $previousBalance = $lastEntry ? $lastEntry->balance : $supplier->opening_balance;

            // For Supplier (Liability): Balance = Prev + Credit - Debit
            $debit = (float) ($data['debit'] ?? 0);
            $credit = (float) ($data['credit'] ?? 0);
            $newBalance = $previousBalance + $credit - $debit;

            return SupplierAccount::create([
                'supplier_id' => $supplierId,
                'date' => $data['date'] ?? now(),
                'description' => $data['description'],
                'debit' => $debit,
                'credit' => $credit,
                'balance' => $newBalance,
                'reference_type' => $data['reference_type'] ?? null,
                'reference_id' => $data['reference_id'] ?? null,
                'status' => 'active',
            ]);
        });
    }

    /**
     * Recalculate balances for a customer or supplier.
     */
    public function recalculateBalance(string $type, int $id): void
    {
        if ($type === 'customer') {
            app(AccountBalanceService::class)->recalculateBalance($id);
        } else {
            $this->recalculateSupplierBalance($id);
        }
    }

    public function recalculateSupplierBalance(int $supplierId): void
    {
        $supplier = Supplier::find($supplierId);
        if (! $supplier) {
            return;
        }

        $runningBalance = $supplier->opening_balance ?? 0;

        $entries = SupplierAccount::where('supplier_id', $supplierId)
            ->where('status', 'active')
            ->orderBy('id')
            ->get();

        foreach ($entries as $entry) {
            // For Supplier: Add Credit, Subtract Debit
            $runningBalance += ($entry->credit - $entry->debit);

            if (abs($entry->balance - $runningBalance) > 0.001) {
                $entry->balance = $runningBalance;
                $entry->saveQuietly();
            }
        }
    }
}
