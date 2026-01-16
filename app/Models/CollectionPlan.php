<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionPlan extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'collector_id',
        'name',
        'date',
        'collection_type',
        'type',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
        'plan_date' => 'date',
    ];

    /**
     * Get the collector associated with this plan.
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

    /**
     * Get all items in this collection plan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(CollectionPlanItem::class);
    }

    /**
     * Get total expected amount from this plan.
     */
    public function getTotalExpectedAmount(): float
    {
        return $this->items->sum('expected_amount');
    }

    /**
     * Get total actual collected amount.
     */
    public function getTotalCollectedAmount(): float
    {
        return $this->items
            ->filter(fn($item) => $item->collection_id !== null)
            ->sum(function ($item) {
                return $item->collection?->amount ?? 0;
            });
    }

    /**
     * Calculate completion percentage based on amounts.
     */
    public function getProgressPercentage(): float
    {
        $expected = $this->getTotalExpectedAmount();

        if ($expected <= 0) {
            return 0;
        }

        $collected = $this->getTotalCollectedAmount();

        $percentage = ($collected / $expected) * 100;

        return round(min($percentage, 100), 1);
    }
}
