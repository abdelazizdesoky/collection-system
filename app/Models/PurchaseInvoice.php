<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseInvoice extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'supplier_id',
        'warehouse_id',
        'invoice_date',
        'due_date',
        'subtotal',
        'discount',
        'tax',
        'total',
        'payment_type',
        'paid_amount',
        'remaining',
        'installment_interest',
        'installment_duration',
        'installment_start_date',
        'status',
        'notes',
        'created_by',
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
     * Get the supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
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
     * Get invoice items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    /**
     * Get payments.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(PurchasePayment::class);
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
}
