<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class EmailCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_name',
        'email_subject',
        'email_body',
        'cc',
        'bcc',
        'target_emails',
        'status',
    ];

    protected $casts = [
        'target_emails' => 'array', // Tự động giải mã JSON thành mảng PHP
    ];
}
