<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CollectionPlan extends Model
{
    use HasFactory, Auditable, SoftDeletes;

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



    public function getActualCollectedAmount(): float
    {
        return $this->getTotalCollectedAmount();
    }

    /**
     * Scope to get plans for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        // $date is a Carbon instance
        return $query->where(function ($q) use ($date) {
            $q->where(function ($dq) use ($date) {
                $dq->where('type', 'daily')
                   ->whereDate('date', $date);
            })->orWhere(function ($wq) use ($date) {
                $wq->where('type', 'weekly')
                   ->whereDate('date', '<=', $date)
                   ->whereDate('date', '>=', $date->copy()->subDays(6));
            })->orWhere(function ($mq) use ($date) {
                $mq->where('type', 'monthly')
                   ->whereMonth('date', $date->month)
                   ->whereYear('date', $date->year);
            });
        });
    }

    /**
     * Get a descriptive date label for the plan.
     */
    public function getDateLabelAttribute(): string
    {
        if (!$this->date) return '';

        if ($this->type === 'weekly') {
            $end = $this->date->copy()->addDays(6);
            return $this->date->format('Y/m/d') . ' - ' . $end->format('Y/m/d');
        }

        if ($this->type === 'monthly') {
            return $this->date->format('Y/m');
        }

        return $this->date->format('Y/m/d');
    }
}
