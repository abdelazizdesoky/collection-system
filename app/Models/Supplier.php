<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'opening_balance',
        'balance_type',
    ];

    protected function casts(): array
    {
        return [
            'opening_balance' => 'decimal:2',
        ];
    }

    /**
     * Get purchase invoices for this supplier.
     */
    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    /**
     * Get purchase payments.
     */
    public function purchasePayments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class);
    }

    /**
     * Get all supplier account ledger entries.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(SupplierAccount::class);
    }

    /**
     * Get the current balance of the supplier from the ledger.
     */
    public function getCurrentBalance(): float
    {
        $lastAccount = $this->accounts()->where('status', 'active')->latest('id')->first();

        return (float) ($lastAccount?->balance ?? $this->opening_balance);
    }

    /**
     * Get the current balance of the supplier.
     */
    public function getCurrentBalanceAttribute(): float
    {
        $totalInvoices = $this->purchaseInvoices()
            ->where('status', 'confirmed')
            ->sum('total');

        $totalPayments = $this->purchasePayments()->sum('amount');

        $opening = $this->balance_type === 'credit'
            ? $this->opening_balance
            : -$this->opening_balance;

        return (float) ($opening + $totalInvoices - $totalPayments);
    }
}
