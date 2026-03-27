<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SaleInvoice extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'customer_id',
        'warehouse_id',
        'invoice_date',
        'due_date',
        'installment_interest',
        'installment_duration',
        'installment_start_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_type',
        'paid_amount',
        'remaining',
        'status',
        'notes',
        'created_by',
        'is_adhoc',
        'reviewer_notes',
        'reviewed_by_id',
        'reviewed_at',
    ];

    protected function casts(): array
    {
        return [
            'invoice_date' => 'date',
            'due_date' => 'date',
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'tax' => 'decimal:2',
            'total' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'remaining' => 'decimal:2',
            'installment_interest' => 'decimal:2',
            'installment_start_date' => 'date',
        ];
    }

    /**
     * Get the customer.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the warehouse.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    /**
     * Get the user who created the invoice.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the reviewer who approved/rejected this invoice.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by_id');
    }

    /**
     * Get invoice items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(SaleInvoiceItem::class);
    }

    /**
     * Get payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(SalePayment::class);
    }

    /**
     * Calculate totals from items.
     */
    public function recalculateTotals(): void
    {
        $this->subtotal = $this->items()->sum('total');
        $this->total = $this->subtotal - $this->discount + $this->tax;
        $this->paid_amount = $this->payments()->sum('amount');
        $this->remaining = $this->total - $this->paid_amount;
        $this->save();
    }

    /**
     * Get payment type label in Arabic.
     */
    public function getPaymentTypeLabelAttribute(): string
    {
        return match ($this->payment_type) {
            'cash' => 'نقدي',
            'credit' => 'آجل',
            'installment' => 'تقسيط',
            default => $this->payment_type,
        };
    }
}
