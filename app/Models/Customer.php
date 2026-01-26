<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'phone',
        'address',
        'opening_balance',
        'balance_type',
        'area_id',
        'collector_id',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
    ];

    /**
     * Get the area that owns the customer.
     */
    public function area(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    /**
     * Get the collector responsible for this customer.
     */
    public function collector(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

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
     * Get all visit plan items for this customer.
     */
    public function visitPlanItems(): HasMany
    {
        return $this->hasMany(VisitPlanItem::class);
    }

    /**
     * Get all visits for this customer.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the current balance of the customer.
     */
    public function getCurrentBalance(): string
    {
        $lastAccount = $this->accounts()->latest()->first();

        return $lastAccount?->balance ?? $this->opening_balance;
    }

    /**
     * Get installment plans for this customer.
     */
    public function installmentPlans(): HasMany
    {
        return $this->hasMany(InstallmentPlan::class);
    }

    /**
     * Get due installments for this customer.
     */
    public function getDueInstallmentsAttribute()
    {
        return Installment::whereIn('installment_plan_id', $this->installmentPlans()->pluck('id'))
            ->where('status', 'pending')
            ->whereDate('due_date', '<=', now())
            ->orderBy('due_date')
            ->get();
    }

    /**
     * Check if customer has due installments.
     */
    public function hasDueInstallments(): bool
    {
        return $this->due_installments->isNotEmpty();
    }
}
