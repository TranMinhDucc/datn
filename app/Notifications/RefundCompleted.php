<?php

namespace App\Notifications;

use App\Models\Refund;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundCompleted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Refund $refund) {}

    public function via($notifiable)
    {
        return ['mail', 'database']; // gửi email và lưu DB notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Hoàn tiền cho đơn hàng #' . $this->refund->order_id)
            ->greeting('Xin chào ' . $notifiable->name)
            ->line('Chúng tôi đã xử lý hoàn tiền cho đơn hàng #' . $this->refund->order_id)
            ->line('Số tiền: ' . number_format($this->refund->amount) . 'đ')
            ->line('Mã giao dịch: ' . ($this->refund->bank_ref ?? '—'))
            ->line('Ngày chuyển: ' . $this->refund->transferred_at->format('d/m/Y H:i'))
            ->action('Xem đơn hàng', url('/orders/' . $this->refund->order_id))
            ->line('Cảm ơn bạn đã mua sắm cùng chúng tôi!');
    }

    public function toArray($notifiable)
    {
        return [
            'refund_id' => $this->refund->id,
            'order_id'  => $this->refund->order_id,
            'amount'    => $this->refund->amount,
            'status'    => $this->refund->status,
        ];
    }
}
