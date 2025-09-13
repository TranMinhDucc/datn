<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnRequestItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'return_request_id',
        'order_item_id',
        'quantity',
    ];

    // Mỗi item thuộc về một yêu cầu hoàn/đổi
    public function returnRequest()
    {
        return $this->belongsTo(ReturnRequest::class);
    }

    // Mỗi item liên kết với một sản phẩm trong đơn hàng
    // public function orderItem()
    // {
    //     return $this->belongsTo(OrderItem::class);
    // }
    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class, 'order_item_id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
