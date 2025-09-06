<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Review extends Model
{
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_item_id',
        'parent_id',
        'rating',
        'comment',
        'verified_purchase',
        'approved', // Cho phép cập nhật duyệt/bỏ duyệt
    ];

    protected $casts = [
        'verified_purchase' => 'boolean',
        'approved' => 'boolean',
        'rating' => 'integer',
    ];

    /** 
     * Quan hệ với User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Quan hệ với Product
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Quan hệ phản hồi (replies)
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    /**
     * Quan hệ với review cha (parent)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }

    /**
     * Scope chỉ lấy review đã duyệt
     */
    public function scopeApproved($query)
    {
        return $query->where('approved', true);
    }
}
