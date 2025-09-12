<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id',
        'variant_name',
        'price',
        'quantity',
        'reserved_quantity',
        'sku',
        'weight',
        'length',
        'width',
        'height',
        'is_active'
    ];

    // Quan hệ với sản phẩm
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Quan hệ với các thuộc tính biến thể
    public function variantOptions()
    {
        return $this->hasMany(ProductVariantOption::class);
    }
    public function options()
    {
        return $this->hasMany(ProductVariantOption::class, 'product_variant_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_variant_id');
    }
    public function getAvailableQuantityAttribute()
    {
        return max(0, $this->quantity - $this->reserved_quantity);
    }
}
