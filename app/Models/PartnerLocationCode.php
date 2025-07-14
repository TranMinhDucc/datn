<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartnerLocationCode extends Model
{
    protected $fillable = [
        'type',
        'location_id',
        'partner_code',
        'partner_id',
    ];
}
