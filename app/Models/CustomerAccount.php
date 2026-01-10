<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'date',
        'description',
        'debit',
        'credit',
        'balance',
        'reference_type',
        'reference_id',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Get the customer associated with this account entry.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the referenced model (polymorphic).
     */
    public function referenceable()
    {
        return match ($this->reference_type) {
            'Collection' => Collection::find($this->reference_id),
            'Cheque' => Cheque::find($this->reference_id),
            default => null,
        };
    }

    /**
     * Check if this is a debit entry.
     */
    public function isDebit(): bool
    {
        return (float)$this->debit > 0;
    }

    /**
     * Check if this is a credit entry.
     */
    public function isCredit(): bool
    {
        return (float)$this->credit > 0;
    }
}
