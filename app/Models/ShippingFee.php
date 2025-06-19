<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingFee extends Model
{
    protected $fillable = ['shipping_zone_id', 'shipping_method_id', 'price', 'free_shipping_minimum'];
    public function zone()
    {
        return $this->belongsTo(ShippingZone::class,'shipping_zone_id');
    }

    public function method()
    {
        return $this->belongsTo(ShippingMethod::class,'shipping_method_id');
    }
}
