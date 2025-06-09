<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'max_usage',
        'usage_count',
        'start_date',
        'end_date',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'max_usage' => 'integer',
        'usage_count' => 'integer',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Optional: check if coupon is valid (for use in controller or business logic)
    public function isValid(): bool
    {
        $now = Carbon::now();
        return $this->usage_count < $this->max_usage &&
               ($this->start_date === null || $now->gte($this->start_date)) &&
               ($this->end_date === null || $now->lte($this->end_date));
    }
}
