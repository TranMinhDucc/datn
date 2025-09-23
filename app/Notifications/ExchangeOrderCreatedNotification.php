<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExchangeOrderCreatedNotification extends Notification
{
    use Queueable;

    public $exchangeOrder;

    public function __construct($exchangeOrder)
    {
        $this->exchangeOrder = $exchangeOrder;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📦 Đơn đổi mới #' . $this->exchangeOrder->order_code)
            ->line('Shop đã tạo đơn đổi mới #' . $this->exchangeOrder->order_code . ' cho bạn.')
            ->action('Xem đơn hàng', url('/account/orders/' . $this->exchangeOrder->id));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Đã tạo đơn đổi mới #' . $this->exchangeOrder->order_code,
            'order_id' => $this->exchangeOrder->id,
        ];
    }
}
