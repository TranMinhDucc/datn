<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
      protected $fillable = [
        'subtitle',
        'title',
        'description',
        'main_image',
        'sub_image_1',
        'sub_image_1_name',
        'sub_image_1_price',
        'sub_image_2',
        'sub_image_2_name',
        'sub_image_2_price',
        'status',
    ];

   
}
