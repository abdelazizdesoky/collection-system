<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class VisitPlanItem extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'visit_plan_id',
        'customer_id',
        'priority',
        'status',
        'notes',
    ];

    /**
     * Get the visit plan this item belongs to.
     */
    public function visitPlan(): BelongsTo
    {
        return $this->belongsTo(VisitPlan::class);
    }

    /**
     * Get the customer for this plan item.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the visit record for this item (if visited).
     */
    public function visit(): HasOne
    {
        return $this->hasOne(Visit::class);
    }

    /**
     * Get the status label in Arabic.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'معلق',
            'visited' => 'تمت الزيارة',
            'skipped' => 'تم التخطي',
            default => $this->status,
        };
    }

    /**
     * Get the status color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400',
            'visited' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            'skipped' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    /**
     * Check if this item has been visited.
     */
    public function getIsVisitedAttribute(): bool
    {
        return $this->status === 'visited';
    }
}
