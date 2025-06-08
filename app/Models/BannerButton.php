<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BannerButton extends Model
{
    protected $fillable = ['banner_id', 'ten', 'duong_dan'];

    public function banner()
    {
        return $this->belongsTo(Banner::class);
    }
}
