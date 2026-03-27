<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SupplierAccount extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'supplier_id',
        'date',
        'description',
        'debit',
        'credit',
        'balance',
        'reference_type',
        'reference_id',
        'status',
    ];

    protected $casts = [
        'debit' => 'decimal:2',
        'credit' => 'decimal:2',
        'balance' => 'decimal:2',
        'date' => 'date',
    ];

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Get the supplier associated with this account entry.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Check if this is a debit entry.
     */
    public function isDebit(): bool
    {
        return (float) $this->debit > 0;
    }

    /**
     * Check if this is a credit entry.
     */
    public function isCredit(): bool
    {
        return (float) $this->credit > 0;
    }
}
