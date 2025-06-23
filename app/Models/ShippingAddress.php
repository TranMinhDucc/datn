<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id', 'title', 'address', 'country', 'state', 'city',
        'pincode', 'phone', 'is_default', 'status'
    ];

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => 'Hoạt động',
            self::STATUS_INACTIVE => 'Vô hiệu hóa',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}