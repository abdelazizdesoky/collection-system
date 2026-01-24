<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InstallmentPlan extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'customer_id',
        'invoice_no',
        'total_amount',
        'down_payment',
        'increase_percentage',
        'financed_amount',
        'duration_months',
        'monthly_amount',
        'start_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'down_payment' => 'decimal:2',
        'increase_percentage' => 'decimal:2',
        'financed_amount' => 'decimal:2',
        'monthly_amount' => 'decimal:2',
        'start_date' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function installments(): HasMany
    {
        return $this->hasMany(Installment::class);
    }

    public function getPaidAmountAttribute(): float
    {
        return (float) $this->installments()->where('status', 'paid')->sum('amount');
    }

    public function getRemainingAmountAttribute(): float
    {
        return (float) $this->financed_amount - $this->paid_amount;
    }

    public function getProgressPercentageAttribute(): float
    {
        if ($this->financed_amount <= 0) return 0;
        return round(($this->paid_amount / $this->financed_amount) * 100, 1);
    }
}
