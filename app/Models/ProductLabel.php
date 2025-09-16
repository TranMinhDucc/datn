<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLabel extends Model
{
    protected $fillable = ['image', 'position'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_label_product');
    }
}
