<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Refund extends Model
{
    protected $fillable = [
        'return_request_id',
        'order_id',
        'user_id',
        'amount',
        'breakdown',
        'currency',
        'method',
        'status',
        'bank_ref',
        'transferred_at',
        'created_by',
        'processed_by',
        'note',
    ];

    protected $casts = [
        'amount'         => 'float',
        'breakdown'      => 'array',
        'transferred_at' => 'datetime',
    ];

    // Trạng thái tiện dùng
    public const STATUS_PENDING  = 'pending';
    public const STATUS_DONE     = 'done';
    public const STATUS_FAILED   = 'failed';
    public const STATUS_CANCELED = 'canceled';

    // Quan hệ
    public function rr()
    {
        return $this->belongsTo(ReturnRequest::class, 'return_request_id');
    }
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes hay dùng
    public function scopePending($q)
    {
        return $q->where('status', self::STATUS_PENDING);
    }
    public function scopeForRR($q, $rrId)
    {
        return $q->where('return_request_id', $rrId);
    }
}
