<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
      protected $fillable = [
        'title',
        'subtitle',
        'description',
        'main_image',
        'product_id_1',
        'product_id_2',
        'btn_title',  
        'btn_link',
        'status',
    ];

    /**
     * Quan hệ đến sản phẩm 1
     */
    public function product1()
    {
        return $this->belongsTo(Product::class, 'product_id_1');
    }

    /**
     * Quan hệ đến sản phẩm 2
     */
    public function product2()
    {
        return $this->belongsTo(Product::class, 'product_id_2');
}
}