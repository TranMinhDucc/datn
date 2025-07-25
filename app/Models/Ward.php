<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = ['district_id', 'name', 'code', 'ward_code', 'ghn_id'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }
}
