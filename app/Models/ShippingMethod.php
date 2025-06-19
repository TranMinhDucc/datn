<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'description'];
    public function fees()
    {
        return $this->hasMany(ShippingFee::class);
    }
}
