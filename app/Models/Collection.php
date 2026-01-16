<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collection extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'customer_id',
        'collector_id',
        'amount',
        'payment_type',
        'collection_date',
        'receipt_no',
        'notes',
        'attachment',
        'bank_name',
        'reference_no',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'collection_date' => 'date',
    ];

    /**
     * Get the customer associated with this collection.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the collector who recorded this collection.
     */
    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

    /**
     * Get the customer account ledger entry for this collection.
     */
    public function accountEntry(): BelongsTo
    {
        return $this->belongsTo(
            CustomerAccount::class,
            'id',
            'reference_id'
        )->where('reference_type', 'Collection');
    }

    /**
     * Get the plan item associated with this collection.
     */
    public function planItem(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(CollectionPlanItem::class);
    }

    /**
     * Get the cheque associated with this collection.
     */
    public function cheque(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cheque::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Collection $collection): void {
            // Observer logic moved to Controller for better transaction control
            // and to solve the issue of balance not updating reliably.
        });
    }
}
