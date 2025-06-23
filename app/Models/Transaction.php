<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'user_id',
        'bank_id',
        'transactionID',
        'amount',
        'description',
        'bank',
        'unique_id',
        'created_at',
        'updated_at',
    ];

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
