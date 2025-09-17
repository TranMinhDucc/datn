<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrafficLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'source',
        'referer',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'ip',
        'user_agent',
        'visited_at',
    ];

    protected $dates = ['visited_at'];
}
