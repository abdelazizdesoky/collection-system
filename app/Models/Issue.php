<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Issue extends Model
{
    use HasFactory, Auditable, SoftDeletes;

    protected $fillable = [
        'customer_id',
        'collector_id',
        'visit_id',
        'title',
        'description',
        'status',
        'priority',
        'escalation_reason',
        'resolution_notes',
        'closure_reason',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(Collector::class);
    }

    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    public function history(): HasMany
    {
        return $this->hasMany(IssueHistory::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'معلق',
            'processing' => 'قيد المعالجة',
            'resolved' => 'تم الحل',
            'escalated' => 'تم التصعيد',
            'closed' => 'مغلقة',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-400',
            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
            'resolved' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-400',
            'escalated' => 'bg-rose-100 text-rose-800 dark:bg-rose-900/30 dark:text-rose-400',
            'closed' => 'bg-slate-100 text-slate-800 dark:bg-slate-900/30 dark:text-slate-400',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
