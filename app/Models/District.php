<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class District extends Model
{
    use HasFactory;

    protected $fillable = ['province_id', 'name', 'code', 'district_code', 'ghn_id'];

    public function province()
    {
        return $this->belongsTo(province::class);
    }

    public function wards()
    {
        return $this->hasMany(Ward::class);
    }
}
