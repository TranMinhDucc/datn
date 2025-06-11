<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'code',
        'short_desc',
        'description',
        'import_price',
        'base_price',
        'sale_price',
        'stock_quantity',
        'status',
        'image',
        'category_id',
        'brand_id',
    ];

    // Quan hệ với danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Quan hệ với thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Quan hệ với ảnh phụ
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Quan hệ với biến thể
    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
