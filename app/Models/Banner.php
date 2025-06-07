<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'ten',
        'hinh_anh',
        'mo_ta',
        'ngon_ngu',
        'thu_tu',
        'status',
        'is_app'
    ];

    public function buttons()
    {
        return $this->hasMany(BannerButton::class);
    }
}
