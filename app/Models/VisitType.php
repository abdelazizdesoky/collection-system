<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VisitType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'label', 'is_system'];

    protected $casts = [
        'is_system' => 'boolean',
    ];
}
