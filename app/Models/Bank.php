<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    /** @use HasFactory<\Database\Factories\BankFactory> */
    use HasFactory;

    protected $fillable = [
        'short_name',
        'image',
        'account_name',
        'account_number',
        'password',
        'token',
        'status',
    ];
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
