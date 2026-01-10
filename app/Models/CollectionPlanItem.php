<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionPlanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'collection_plan_id',
        'customer_id',
        'expected_amount',
        'priority',
    ];

    protected $casts = [
        'expected_amount' => 'decimal:2',
    ];

    /**
     * Get the collection plan this item belongs to.
     */
    public function collectionPlan(): BelongsTo
    {
        return $this->belongsTo(CollectionPlan::class);
    }

    /**
     * Get the customer for this plan item.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
