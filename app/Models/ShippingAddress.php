<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'province_id',
        'district_id',
        'ward_id',
        'address',
        'country',
        'pincode',
        'phone',
        'is_default',
        'status',
        'full_name'
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
    public function province()
    {
        return $this->belongsTo(\App\Models\Province::class);
    }

    public function district()
    {
        return $this->belongsTo(\App\Models\District::class);
    }

    public function ward()
    {
        return $this->belongsTo(\App\Models\Ward::class);
    }
}
