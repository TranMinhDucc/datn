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
        'user_id',
        'status',
        'subtotal',
        'total_amount',
        'note_shipper',
        'required_note_shipper',
        'exchange_of_return_request_id',
    ];
    public function exchangeOfReturnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'exchange_of_return_request_id');
    }
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
    public function coupon()
    {
        return $this->belongsTo(Coupon::class, 'coupon_id');
    }
    public function couponUsers()
    {
        return $this->hasMany(CouponUser::class);
    }

    public function coupons()
    {
        return $this->belongsToMany(Coupon::class, 'coupon_user')
            ->withPivot('status')
            ->withTimestamps();
    }
    public static function generateOrderCode()
    {
        do {
            $code = 'ORD' . now()->timestamp . rand(10, 99);
        } while (self::where('order_code', $code)->exists());

        return $code;
    }
    public function adjustments()
    {
        return $this->hasMany(\App\Models\OrderAdjustment::class);
    }
    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class);
    }

    // Tổng cộng/trừ từ adjustments (charge cộng, discount trừ)
    public function getAdjustmentsTotalAttribute()
    {
        return (float) ($this->adjustments()
            ->selectRaw("COALESCE(SUM(CASE WHEN type='charge' THEN amount ELSE -amount END),0) as s")
            ->value('s') ?? 0);
    }

    // Tổng tiền phải thu sau cùng (hàng + VAT + ship + adjustments)
    public function getNetTotalAttribute()
    {
        return (float)$this->subtotal + (float)$this->tax_amount + (float)$this->shipping_fee
            + (float)$this->adjustments_total;
    }

    // Tiền đã thu & đã hoàn (đã complete)
    public function getPaidInAttribute()
    {
        return (float) $this->payments()->where('kind', 'payment')->where('status', 'completed')->sum('amount');
    }
    public function getRefundedOutAttribute()
    {
        return (float) $this->payments()->where('kind', 'refund')->where('status', 'completed')->sum('amount');
    }

    // Số dư: dương = KH còn thiếu; âm = shop còn phải hoàn
    public function getBalanceAttribute()
    {
        return (float)$this->net_total - (float)$this->paid_in;
    }
    public function histories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
