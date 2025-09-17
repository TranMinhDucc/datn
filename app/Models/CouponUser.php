<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CouponUser extends Model
{
    use HasFactory;

    protected $table = 'coupon_user'; // vì tên không có "s" ở cuối

    protected $fillable = [
        'coupon_id',
        'user_id',
        'order_id',
        'status', // nếu sau này bạn thêm cột status
    ];

    // ================== Quan hệ ==================

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
