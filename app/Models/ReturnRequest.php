<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReturnRequest extends Model
{
    use HasFactory;

    // Các trạng thái
    const STATUS_PENDING   = 'pending';
    const STATUS_APPROVED  = 'approved';
    const STATUS_REJECTED  = 'rejected';
    const STATUS_REFUNDED  = 'refunded';
    const STATUS_EXCHANGED = 'exchanged';
    const STATUS_COMPLETED = 'completed'; // ✅ gợi ý thêm

    protected $fillable = [
        'order_id',
        'exchange_order_id',
        'user_id',
        'reason',
        'attachments',
        'type',
        'status',
        'admin_note',
        'handled_by',
        'handled_at',
        'total_refund_amount',
    ];

    protected $casts = [
        'attachments'   => 'array',
        'handled_at'    => 'datetime',
        'total_refund_amount' => 'decimal:2',
    ];

    // Quan hệ với đơn gốc
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    // Quan hệ với đơn đổi (nếu có)
    public function exchangeOrder()
    {
        return $this->belongsTo(Order::class, 'exchange_order_id');
    }

    // Quan hệ với các item trong yêu cầu đổi/trả
    public function items()
    {
        return $this->hasMany(ReturnRequestItem::class, 'return_request_id');
    }

    // Người gửi yêu cầu
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Admin xử lý yêu cầu
    public function handledBy()
    {
        return $this->belongsTo(User::class, 'handled_by');
    }
}
