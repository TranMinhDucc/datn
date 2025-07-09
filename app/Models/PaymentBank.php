<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class PaymentBank extends Model
{
    protected $fillable = [
        'short_name',
        'account_number',
        'account_name',
        'url_api',
        'status',
    ];
}