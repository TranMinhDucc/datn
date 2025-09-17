<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderAdjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'code',
        'label',
        'type',
        'amount',
        'taxable',
        'meta',
        'created_by',
    ];

    protected $casts = [
        'amount'  => 'decimal:2',
        'taxable' => 'boolean',
        'meta'    => 'array',
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
