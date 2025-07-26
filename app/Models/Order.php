<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_code',
        'address_id',
        'payment_method_id',
        'coupon_code',
        'coupon_id',
        'shipping_coupon_id',
        'discount_amount',
        'subtotal',
        'tax_amount',
        'shipping_fee',
        'total_amount',
        'status',
        'is_paid',
        'payment_status',
        'payment_reference',
        'shipping_method',
        'shipping_tracking_code',
        'expected_delivery_date',
        'delivered_at',
        'cancel_reason',
        'note',
        'ip_address',
        'user_agent',
    ];

    
    
    public function user()
{
    return $this->belongsTo(User::class, 'user_id');
}
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    // In Order model
    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'address_id');
    }
    public function address()
    {
        return $this->belongsTo(ShippingAddress::class, 'address_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function shippingLogs()
    {
        return $this->hasMany(ShippingLog::class);
    }
    public function shippingOrder()
    {
        return $this->hasOne(ShippingOrder::class);
    }
}
