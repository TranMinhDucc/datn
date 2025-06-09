<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'price', 'stock', 'status'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(VariantAttribute::class, 'product_variant_options')
            ->withPivot('value');
    }

    public function options()
    {
        return $this->hasMany(ProductVariantOption::class, 'variant_id');
    }
}
