<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IssueHistory extends Model
{
    use HasFactory;

    protected $table = 'issue_history';

    protected $fillable = [
        'issue_id',
        'user_id',
        'comment',
        'old_status',
        'new_status',
    ];

    public function issue(): BelongsTo
    {
        return $this->belongsTo(Issue::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
