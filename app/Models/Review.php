<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    public $timestamps = true; // <-- Cái này mặc định là true, nhưng nếu bạn override thì đảm bảo đúng

    protected $fillable = [
        'user_id',
        'product_id',
        'rating',
        'comment',
        'created_at',
        'verified_purchase',
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
}
