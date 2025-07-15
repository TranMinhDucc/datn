<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ProductDetail;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'label_id',
        'name',
        'slug',
        'description',
        'detailed_description',
        'image',
        'import_price',
        'base_price',
        'sale_price',
        'stock_quantity',
        'rating_avg',
        'is_active',
        'starts_at',
        'ends_at',
        'sale_times',
        'weight',
        'length',
        'width',
        'height',
    ];

    // Quan hệ với danh mục
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function label()
    {
        return $this->hasMany(ProductLabel::class, 'product_id');
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


    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function inventoryTransactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    // Quan hệ với tags
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }
    public function detail()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }
}
