<?php

namespace App\Models;

use App\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use Auditable, HasFactory, SoftDeletes;

    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit_id',
        'description',
        'selling_price',
        'purchase_price',
        'min_stock',
        'reorder_level',
        'is_for_sale',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'selling_price' => 'decimal:2',
            'purchase_price' => 'decimal:2',
            'min_stock' => 'decimal:2',
            'reorder_level' => 'decimal:2',
            'is_for_sale' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the category of this product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    /**
     * Get the unit of this product.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * Get stock items for this product across all warehouses.
     */
    public function stockItems(): HasMany
    {
        return $this->hasMany(StockItem::class);
    }

    /**
     * Get the total stock quantity across all warehouses.
     */
    public function getTotalStockAttribute(): float
    {
        return (float) $this->stockItems()->sum('quantity');
    }

    /**
     * Check if stock is below minimum.
     */
    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->min_stock;
    }

    /**
     * Get sale invoice items.
     */
    public function saleInvoiceItems(): HasMany
    {
        return $this->hasMany(SaleInvoiceItem::class);
    }

    /**
     * Get purchase invoice items.
     */
    public function purchaseInvoiceItems(): HasMany
    {
        return $this->hasMany(PurchaseInvoiceItem::class);
    }

    /**
     * Get stock movements.
     */
    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }
}
