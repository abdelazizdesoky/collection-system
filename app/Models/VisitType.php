<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VisitType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'label', 'is_system'];

    protected $casts = [
        'is_system' => 'boolean',
    ];
}
