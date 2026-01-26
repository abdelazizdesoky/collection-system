<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Visit extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'visit_plan_item_id',
        'collector_id',
        'customer_id',
        'visit_type', // Ideally we migrate this out, but keeping for compatibility if logic relies on it strings
        'visit_type_id', // New relation
        'visit_time',
        'notes',
        'attachment',
        'collection_id',
        'issue_description',
        'issue_status',
        'order_details',
        'order_amount',
    ];

    protected $casts = [
        'visit_time' => 'datetime',
        'order_amount' => 'decimal:2',
    ];

    /**
     * Get the visit plan item.
     */
    public function visitPlanItem(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(VisitPlanItem::class);
    }

    /**

    /**
     * Get the customer visited.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the collection associated with this visit (if collection type).
     */
    public function collection(): BelongsTo
    {
        return $this->belongsTo(Collection::class);
    }

    /**
     * Get the visit type label in Arabic.
     */
    public function getVisitTypeLabelAttribute(): string
    {
        return match ($this->visit_type) {
            'collection' => 'تحصيل',
            'order' => 'عمل أوردر',
            'issue' => 'حل مشكلة',
            'general' => 'زيارة عامة',
            default => $this->visit_type,
        };
    }

    /**
     * Get the visit type color class.
     */
    public function getVisitTypeColorAttribute(): string
    {
        return match ($this->visit_type) {
            'collection' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'order' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'issue' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
            'general' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400',
            default => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
        };
    }

    /**
     * Get the issue status label in Arabic.
     */
    public function getIssueStatusLabelAttribute(): ?string
    {
        if (!$this->issue_status) {
            return null;
        }

        return match ($this->issue_status) {
            'pending' => 'قيد المعالجة',
            'resolved' => 'تم الحل',
            'escalated' => 'تم التصعيد',
            default => $this->issue_status,
        };
    }

    /**
     * Get the visit type icon.
     */
    public function getVisitTypeIconAttribute(): string
    {
        return match ($this->visit_type) {
            'collection' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>',
            'order' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>',
            'issue' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>',
            'general' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>',
            default => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>',
        };
    }
}
