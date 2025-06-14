<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    protected $table = 'product_variant_options';

    public $timestamps = false;

    protected $fillable = ['product_variant_id', 'attribute_id', 'value_id'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function value()
    {
        return $this->belongsTo(AttributeValue::class, 'value_id');
    }
}
