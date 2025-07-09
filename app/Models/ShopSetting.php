<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShopSetting extends Model
{
    protected $table = 'shop_settings';

    protected $fillable = [
        'shop_name',
        'shop_phone',
        'address',
        'province_id',
        'district_id',
        'ward_id',
    ];

    // Nếu bạn muốn truy cập tên tỉnh/huyện/xã qua quan hệ
    public function province()
    {
        return $this->belongsTo(Province::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }
}
