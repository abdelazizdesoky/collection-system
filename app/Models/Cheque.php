<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'cheque_no',
        'bank_name',
        'amount',
        'due_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    /**
     * Get the customer associated with this cheque.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Check if cheque is pending.
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if cheque is cleared.
     */
    public function isCleared(): bool
    {
        return $this->status === 'cleared';
    }

    /**
     * Check if cheque is bounced.
     */
    public function isBounced(): bool
    {
        return $this->status === 'bounced';
    }
}
