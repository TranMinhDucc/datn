<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SupportTicket extends Model
{
    protected $fillable = [
        'user_id','subject','category','priority','order_code','carrier_code',
        'contact_via','contact_time','status',
    ];
    protected $casts = ['contact_via' => 'array'];

    public function user(){ return $this->belongsTo(User::class); }
    public function messages(){ return $this->hasMany(SupportTicketMessage::class)->orderBy('created_at'); }


    public static function statusMap(): array {
        return [
            'open'             => 'Đang mở',
            'waiting_staff'    => 'Chờ nhân viên',
            'waiting_customer' => 'Chờ khách phản hồi',
            'resolved'         => 'Đã xử lý',
            'closed'           => 'Đã đóng',
        ];
    }

    public static function statusColors(): array {
        return [
            'open'             => '#f59e0b',
            'waiting_staff'    => '#64748b',
            'waiting_customer' => '#0ea5e9',
            'resolved'         => '#10b981',
            'closed'           => '#334155',
        ];
    }

    public static function priorityMap(): array {
        return [
            'low'    => 'Thấp',
            'normal' => 'Bình thường',
            'high'   => 'Cao',
            'urgent' => 'Khẩn cấp',
        ];
    }

    public static function categoryMap(): array {
        return [
            'order'   => 'Đơn hàng & vận chuyển',
            'product' => 'Sản phẩm & chất lượng',
            'payment' => 'Thanh toán & hoá đơn',
            'account' => 'Tài khoản & đăng nhập',
            'other'   => 'Khác',
        ];
    }

    // Accessors dùng trong Blade
    public function getStatusLabelAttribute()   { return self::statusMap()[$this->status] ?? $this->status; }
    public function getStatusColorAttribute()   { return self::statusColors()[$this->status] ?? '#64748b'; }
    public function getPriorityLabelAttribute() { return self::priorityMap()[$this->priority] ?? $this->priority; }
    public function getCategoryLabelAttribute() { return self::categoryMap()[$this->category] ?? $this->category; }
}
