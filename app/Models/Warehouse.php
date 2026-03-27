<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    protected $fillable = [
        'name',
        'location',
        'manager',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get stock items in this warehouse.
     */
    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    /**
     * Get stock movements for this warehouse.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    /**
     * Get purchase invoices for this warehouse.
     */
    public function purchaseInvoices(): HasMany
    {
        return $this->hasMany(PurchaseInvoice::class);
    }

    /**
     * Get sale invoices for this warehouse.
     */
    public function saleInvoices(): HasMany
    {
        return $this->hasMany(SaleInvoice::class);
    }
}
