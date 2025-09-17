<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    /** Cho phép fill */
    protected $fillable = [
        'user_id',
        'order_code',

        'exchange_of_return_request_id',

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
        'completed_at',

        'cancel_reason',
        'cancel_reason_by_admin',
        'cancelled_at',

        'note',
        'note_shipper',
        'required_note_shipper',
        'required_note',

        'ip_address',
        'user_agent',

        'return_reason',
        'return_image',

        'refunded_at',
        'paid_at',

        'ghn_order_code',
        'is_exchange',
        'ghn_confirmed',
        'cancel_request',
    ];

    /** Casts để đọc/ghi đúng kiểu */
    protected $casts = [
        'discount_amount'        => 'decimal:2',
        'subtotal'               => 'decimal:2',
        'tax_amount'             => 'decimal:2',
        'shipping_fee'           => 'decimal:2',
        'total_amount'           => 'decimal:2',

        'is_paid'                => 'boolean',
        'is_exchange'            => 'boolean',
        'ghn_confirmed'          => 'boolean',
        'cancel_request'         => 'boolean',

        'expected_delivery_date' => 'date',
        'delivered_at'           => 'datetime',
        'completed_at'           => 'datetime',
        'cancelled_at'           => 'datetime',
        'refunded_at'            => 'datetime',
        'paid_at'                => 'datetime',
        'created_at'             => 'datetime',
        'updated_at'             => 'datetime',
    ];

    /* =========================
     * Quan hệ
     * ========================= */

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function exchangeOfReturnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'exchange_of_return_request_id');
    }

    public function items() // giữ cho code cũ
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderItems() // alias
    {
        return $this->hasMany(OrderItem::class);
    }

    public function shippingAddress()
    {
        return $this->belongsTo(ShippingAddress::class, 'address_id');
    }

    public function address() // alias
    {
        return $this->belongsTo(ShippingAddress::class, 'address_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
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

    public function adjustments()
    {
        return $this->hasMany(OrderAdjustment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function refunds()
    {
        return $this->hasMany(Refund::class);
    }

    /* =========================
     * Helper / Business logic
     * ========================= */

    /** Tạo mã đơn */
    public static function generateOrderCode()
    {
        do {
            $code = 'ORD' . now()->timestamp . rand(10, 99);
        } while (self::where('order_code', $code)->exists());

        return $code;
    }

    /** Tổng cộng/trừ từ adjustments (charge +, discount -) */
    public function getAdjustmentsTotalAttribute(): float
    {
        return (float) ($this->adjustments()
            ->selectRaw("COALESCE(SUM(CASE WHEN type='charge' THEN amount ELSE -amount END),0) as s")
            ->value('s') ?? 0);
    }

    /** Tổng phải thu sau cùng = hàng + VAT + ship + adjustments */
    public function getNetTotalAttribute(): float
    {
        return (float) ($this->subtotal ?? 0)
            + (float) ($this->tax_amount ?? 0)
            + (float) ($this->shipping_fee ?? 0)
            + (float) $this->adjustments_total;
    }

    /** Đã thu (chỉ các payment đã completed) */
    public function getPaidInAttribute(): float
    {
        return (float) $this->payments()
            ->where('kind', 'payment')
            ->where('status', 'completed')
            ->sum('amount');
    }

    /**
     * Đã hoàn: gồm
     *  - Refunds từ bảng refunds có status = done
     *  - Các khoản trong payments có kind = 'refund' và status = completed (nếu có)
     */
    public function getRefundedOutAttribute(): float
    {
        $fromRefunds = (float) $this->refunds()->where('status', 'done')->sum('amount');
        $fromPayments = (float) $this->payments()
            ->where('kind', 'refund')
            ->where('status', 'completed')
            ->sum('amount');

        return $fromRefunds + $fromPayments;
    }

    /** Số dư (dương = KH còn thiếu, âm = cần hoàn) */
    public function getBalanceAttribute(): float
    {
        return $this->net_total - $this->paid_in - $this->refunded_out;
    }

    /** Lịch sử trạng thái đơn */
    public function histories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id');
    }
}
