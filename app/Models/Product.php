<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'brand_id',
        'label_id',
        'name',
        'slug',
        'sku',
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
        'size_chart',
        'is_special_offer'
    ];

    // Danh mục
    public function category()
    {
        return $this->belongsTo(Category::class)->withTrashed();
    }

    // Label CHÍNH (do có cột label_id trên bảng products)
    public function label()
    {
        return $this->belongsTo(ProductLabel::class, 'label_id');
    }

    // Nhiều label qua pivot (nếu bạn dùng cả 2 cơ chế)
    public function labels()
    {
        return $this->belongsToMany(ProductLabel::class, 'product_label_product');
    }

    // Thương hiệu
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    // Ảnh phụ
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    // Biến thể
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

    // TAGS
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tag', 'product_id', 'tag_id')->withTimestamps();
    }

    // Chi tiết (giữ 1 tên thôi là đủ)
    public function productDetails()
    {
        return $this->hasMany(ProductDetail::class, 'product_id', 'id');
    }

    // ===== Scopes lọc theo tag =====

    // ANY: có ít nhất 1 tag trong danh sách
    // app/Models/Product.php

    // ANY: có ÍT NHẤT 1 tag trong danh sách
    public function scopeWithAnyTags($q, array $idsOrSlugs)
    {
        $byId  = is_numeric($idsOrSlugs[0] ?? null);
        $col   = $byId ? 'tags.id' : 'tags.slug';
        return $q->whereHas('tags', fn($t) => $t->whereIn($col, $idsOrSlugs));
    }

    // ALL: có ĐỦ tất cả tag trong danh sách (>= count yêu cầu)
    // KHÔNG dùng alias 'as t0', 'as t1'...
    public function scopeWithAllTags($q, array $idsOrSlugs)
    {
        $byId  = is_numeric($idsOrSlugs[0] ?? null);
        $col   = $byId ? 'tags.id' : 'tags.slug';
        return $q->whereHas('tags', fn($t) => $t->whereIn($col, $idsOrSlugs), '>=', count($idsOrSlugs));
    }


    /*
    // Option B (gọn 1 câu): đếm số tag khớp phải >= số tag yêu cầu
    // Chỉ dùng khi bạn truyền toàn ID (khuyến nghị convert slug -> id trước ở Controller)
    public function scopeWithAllTags($q, array $tagIds)
    {
        return $q->whereHas('tags', fn($t) => $t->whereIn('tags.id', $tagIds), '>=', count($tagIds));
    }
    */
}
