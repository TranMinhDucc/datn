<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnRequestItem extends Model
{
    use HasFactory;

    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_RETURNED  = 'returned';
    const STATUS_REFUNDED  = 'refunded';
    const STATUS_EXCHANGED = 'exchanged';

    protected $fillable = [
        'return_request_id',
        'order_item_id',
        'quantity',
        'approved_quantity',
        'status',
        'reason',
        'attachments',
    ];

    protected $casts = [
        'attachments' => 'array',
    ];

    // Mỗi item thuộc về một yêu cầu đổi/trả
    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class, 'return_request_id');
    }

    // Mỗi item liên kết với một sản phẩm trong đơn hàng
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }

    // Truy cập nhanh tới Product (qua orderItem)
    public function product()
    {
        return $this->hasOneThrough(
            Product::class,
            OrderItem::class,
            'id',           // local key on order_items
            'id',           // local key on products
            'order_item_id', // foreign key on return_request_items
            'product_id'    // foreign key on order_items
        );
    }
    public function exchangeVariant()
    {
        return $this->belongsTo(ProductVariant::class, 'exchange_variant_id');
    }
    public function actions(): HasMany
    {
        return $this->hasMany(ReturnRequestItemAction::class);
    }
}
