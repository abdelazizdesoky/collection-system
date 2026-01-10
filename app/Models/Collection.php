<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Collection extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'collector_id',
        'amount',
        'payment_type',
        'collection_date',
        'receipt_no',
        'notes',
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
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Collection $collection): void {
            // Create customer account ledger entry
            $previousBalance = $collection->customer->accounts()->latest()->first()?->balance 
                ?? $collection->customer->opening_balance;

            $newBalance = (float)$previousBalance - (float)$collection->amount;

            CustomerAccount::create([
                'customer_id' => $collection->customer_id,
                'date' => $collection->collection_date,
                'description' => "Payment received - Receipt #{$collection->receipt_no}",
                'debit' => 0,
                'credit' => $collection->amount,
                'balance' => $newBalance,
                'reference_type' => 'Collection',
                'reference_id' => $collection->id,
            ]);
        });
    }
}
