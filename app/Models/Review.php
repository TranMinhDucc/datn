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
        'parent_id',
        'rating',
        'comment',
        'verified_purchase',
        'approved', // <-- Cho phép cập nhật duyệt/bỏ duyệt
    ];

    // Quan hệ với User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Quan hệ với Product
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    // Quan hệ phản hồi: Review có thể có nhiều phản hồi
    public function replies(): HasMany
    {
        return $this->hasMany(Review::class, 'parent_id');
    }

    // Quan hệ với review cha nếu đây là phản hồi
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Review::class, 'parent_id');
    }
    public function scopeApproved($query)
{
    return $query->where('approved', 1);
}

}


