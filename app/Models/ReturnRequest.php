<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'exchange_order_id',
        'reason',
        'attachments',
        'type',
        'status',
        'processed_at',
    ];

    protected $casts = [
        'attachments' => 'array', // để Laravel tự động xử lý kiểu JSON
        'processed_at' => 'datetime',
    ];

    // Quan hệ với đơn hàng
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class);
    }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function originalOrder()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
    public function exchangeOrder()
    {
        return $this->belongsTo(Order::class, 'exchange_order_id');
    }
}
