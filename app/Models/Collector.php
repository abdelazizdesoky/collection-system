<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Collector extends Model
{
    use HasFactory, Auditable;

    protected $fillable = [
        'user_id',
        'code',
        'name',
        'phone',
        'address'
    ];

    /**
     * Get the collector's name from the associated user.
     */
    public function getNameAttribute(): string
    {
        return $this->user->name ?? 'Unknown Collector';
    }

    /**
     * Get the user account for this collector.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'collector_id', 'id');
    }

    /**
     * Get all collection plans for this collector.
     */
    public function collectionPlans(): HasMany
    {
        return $this->hasMany(CollectionPlan::class);
    }

    /**
     * Get all collections recorded by this collector.
     */
    public function collections(): HasMany
    {
        return $this->hasMany(Collection::class);
    }

    /**
     * Get all visit plans for this collector.
     */
    public function visitPlans(): HasMany
    {
        return $this->hasMany(VisitPlan::class);
    }

    /**
     * Get all visits recorded by this collector.
     */
    public function visits(): HasMany
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Get the customers assigned to this collector.
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }
}
