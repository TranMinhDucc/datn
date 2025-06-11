<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VariantAttribute extends Model
{
    protected $table = 'variant_attributes';

    protected $fillable = ['name'];
    public function values() {
    return $this->hasMany(VariantValue::class, 'attribute_id');
}
}


