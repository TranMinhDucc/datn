<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SupportMessageAttachment extends Model
{
    protected $fillable = ['support_ticket_message_id','path','original_name','mime','size'];
    protected $appends  = ['url'];

    public function message(){ return $this->belongsTo(SupportTicketMessage::class,'support_ticket_message_id'); }

    // ✅ luôn trả về /storage/... bám theo host đang truy cập (127.0.0.1:8000 hay datn.test đều ok)
    public function getUrlAttribute()
    {
        return asset('storage/'.$this->path);
    }
}
