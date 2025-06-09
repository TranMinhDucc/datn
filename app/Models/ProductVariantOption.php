<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariantOption extends Model
{
    protected $table = 'product_variant_options';

    public $timestamps = false; // vì bảng này không cần created_at / updated_at

    protected $fillable = [
        'variant_id',
        'value_id',
    ];

    // Optional: quan hệ ngược lại
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function value()
    {
        return $this->belongsTo(VariantValue::class, 'value_id');
    }
}

