<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicketMessage extends Model
{
    protected $fillable = ['support_ticket_id','user_id','is_staff','body','seen_at'];
    protected $casts = ['seen_at'=>'datetime'];

    public function ticket(){ return $this->belongsTo(SupportTicket::class,'support_ticket_id'); }
    public function user(){ return $this->belongsTo(User::class); }
    public function attachments(){ return $this->hasMany(SupportMessageAttachment::class); }
}
