<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RefundTransferredNotification extends Notification
{
    use Queueable;

    public $refund;

    public function __construct($refund)
    {
        $this->refund = $refund;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('💰 Hoàn tiền thành công cho đơn #' . $this->refund->order->order_code)
            ->line('Khoản tiền ' . number_format($this->refund->amount, 0, ',', '.') . '₫ đã được hoàn về tài khoản của bạn.')
            ->action('Xem chi tiết hoàn tiền', url('/account/return-requests/' . $this->refund->return_request_id));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Đã hoàn tiền ' . number_format($this->refund->amount, 0, ',', '.') . '₫',
            'refund_id' => $this->refund->id,
            'order_id' => $this->refund->order_id,
        ];
    }
}
