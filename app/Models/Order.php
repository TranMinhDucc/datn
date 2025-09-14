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
        'payment_method',
        'payment_reference',
        'momo_trans_id',
        'momo_order_id',
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
        'shipping_method',
        'shipping_tracking_code',
        'expected_delivery_date',
        'delivered_at',
        'cancel_reason',
        'note',
        'ip_address',
        'user_agent',
        'return_reason',
        'return_image',
        'refunded_at',
        'paid_at',
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
    public function originalOrder()
    {
        return $this->belongsTo(Order::class, 'exchanged_from_order_id');
    }

    public function exchangeOrders()
    {
        return $this->hasMany(Order::class, 'exchanged_from_order_id');
    }
    public function returnRequest()
    {
        return $this->hasOne(ReturnRequest::class, 'order_id');
    }
    public function returnRequests()
    {
        return $this->hasMany(ReturnRequest::class, 'order_id');
    }
    public function histories()
    {
        return $this->hasMany(OrderStatusHistory::class)->orderBy('created_at', 'asc');
    }
}
