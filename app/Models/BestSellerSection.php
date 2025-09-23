<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BestSellerSection extends Model
{
    protected $fillable = [
        'title_small','title_main','subtitle',
        'btn_text','btn_url',
        'left_image','right_image',
        'side_title','side_offer_title','side_offer_desc','side_offer_code',
        'is_active'
    ];
}
