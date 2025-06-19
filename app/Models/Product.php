<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'name',
        'slug',
        'description',
        'image',
        'import_price',
        'base_price',
        'sale_price',
        'stock_quantity',
        'rating_avg',
        'is_active',
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


    // Quan hệ với tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }
}
