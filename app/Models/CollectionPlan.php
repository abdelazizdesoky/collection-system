<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CollectionPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'collector_id',
        'name',
        'date',
        'collection_type',
        'type',
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
    public function getTotalExpectedAmount(): string
    {
        return $this->items()->sum('expected_amount');
    }
}
