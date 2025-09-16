<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingAddress extends Model
{
    use SoftDeletes;
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
    protected $dates = ['deleted_at'];
    public function orders()
    {
        return $this->hasMany(Order::class, 'address_id');
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
