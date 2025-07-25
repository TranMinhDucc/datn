<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingOrder extends Model
{
    protected $fillable = [
        'order_id',
        'shipping_partner',
        'shipping_code',
        'status',
        'note',
        'request_payload',
        'response_payload',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
