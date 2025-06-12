<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantValue extends Model
{
    protected $table = 'variant_values';

    protected $fillable = ['attribute_id', 'value'];
    public function attribute() {
    return $this->belongsTo(VariantAttribute::class, 'attribute_id');
}
}
