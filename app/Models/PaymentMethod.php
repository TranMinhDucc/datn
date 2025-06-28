<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'active',
    ];

    // Ví dụ: Quan hệ với orders
    public function orders() {
        return $this->hasMany(Order::class);
    }

    // Nếu có cả bảng nạp tiền

}

