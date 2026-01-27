<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitPlan extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'collector_id',
        'created_by',
        'name',
        'start_date',
        'end_date',
        'frequency',
        'status',
        'notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the collector associated with this plan.
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

    /**
     * Get the user who created this plan.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all items in this visit plan.
     */
    public function items(): HasMany
    {
        return $this->hasMany(VisitPlanItem::class);
    }

    /**
     * Get the frequency label in Arabic.
     */
    public function getFrequencyLabelAttribute(): string
    {
        return match ($this->frequency) {
            'daily' => 'يومي',
            'weekly' => 'أسبوعي',
            'monthly' => 'شهري',
            default => $this->frequency,
        };
    }

    /**
     * Get the status label in Arabic.
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'open' => 'مفتوحة',
            'in_progress' => 'جارية',
            'closed' => 'مغلقة',
            default => $this->status,
        };
    }

    /**
     * Get the status color class.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'open' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'in_progress' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
            'closed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    /**
     * Calculate completion percentage.
     */
    public function getProgressPercentage(): float
    {
        $total = $this->items->count();

        if ($total <= 0) {
            return 0;
        }

        $visited = $this->items->where('status', 'visited')->count();

        return round(($visited / $total) * 100, 1);
    }

    /**
     * Check if this plan is for today.
     */
    public function isForToday(): bool
    {
        $today = today();

        if ($this->frequency === 'daily') {
            return $this->start_date->isSameDay($today);
        }

        return $today->between($this->start_date, $this->end_date ?? $this->start_date);
    }

    /**
     * Scope to get plans for a specific date.
     */
    public function scopeForDate($query, $date)
    {
        return $query->where(function ($q) use ($date) {
            $q->where(function ($dq) use ($date) {
                $dq->where('frequency', 'daily')
                   ->whereDate('start_date', $date);
            })->orWhere(function ($wq) use ($date) {
                $wq->whereIn('frequency', ['weekly', 'monthly'])
                   ->whereDate('start_date', '<=', $date)
                   ->whereDate('end_date', '>=', $date);
            });
        });
    }

    /**
     * Get a descriptive date label for the plan.
     */
    public function getDateLabelAttribute(): string
    {
        $start = $this->start_date->format('Y/m/d');
        
        if ($this->frequency === 'daily' || !$this->end_date || $this->start_date->isSameDay($this->end_date)) {
            return $start;
        }

        return $start . ' - ' . $this->end_date->format('Y/m/d');
    }
}
