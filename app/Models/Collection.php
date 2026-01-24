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
        'is_approved',
        'print_count',
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
     * Increment the print count and check if limit is reached.
     */
    public function incrementPrintCount(): bool
    {
        if ($this->print_count >= 3) {
            return false;
        }

        $this->increment('print_count');
        return true;
    }

    /**
     * Get the receipt number with version suffix if printed more than once.
     */
    public function getFormattedReceiptNoAttribute(): string
    {
        if ($this->print_count <= 1) {
            return $this->receipt_no;
        }

        return "{$this->receipt_no}-{$this->print_count}";
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Collection $collection): void {
            // Observer logic moved to Controller
        });
    }
}
