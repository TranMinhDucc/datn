<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount',
        'amount',
        'used',
        'product_id',
        'min',
        'max',
        'expired_at',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'coupon_used')
            ->withPivot('trans_id')
            ->withTimestamps();
    }
}
