<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'kind',
        'method',
        'amount',
        'status',
        'note',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta'   => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
