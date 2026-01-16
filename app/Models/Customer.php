<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'opening_balance',
        'balance_type',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
    ];

    /**
     * Get all collections for this customer.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * Get all cheques for this customer.
     */
    public function cheques(): HasMany
    {
        return $this->hasMany(Cheque::class);
    }

    /**
     * Get all collection plan items for this customer.
     */
    public function collectionPlanItems(): HasMany
    {
        return $this->hasMany(CollectionPlanItem::class);
    }

    /**
     * Get all customer account ledger entries.
     */
    public function accounts(): HasMany
    {
        return $this->hasMany(CustomerAccount::class);
    }

    /**
     * Get the current balance of the customer.
     */
    public function getCurrentBalance(): string
    {
        $lastAccount = $this->accounts()->latest()->first();

        return $lastAccount?->balance ?? $this->opening_balance;
    }
}
