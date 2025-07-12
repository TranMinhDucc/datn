<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Province extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'province_code', 'code', 'ghn_id'];


    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
