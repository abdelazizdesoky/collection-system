<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CollectionPlanItem extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'collection_plan_id',
        'customer_id',
        'expected_amount',
        'priority',
        'status',
        'collection_id',
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

    /**
     * Get the collection for this plan item (if collected).
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }
}
