<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReturnRequestCreatedNotification extends Notification
{
    use Queueable;

    public $returnRequest;

    public function __construct($returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database']; // có thể thêm telegram, slack...
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('📦 Yêu cầu hoàn/đổi hàng #' . $this->returnRequest->id)
            ->line('Bạn vừa tạo yêu cầu hoàn/đổi hàng #' . $this->returnRequest->id)
            ->action('Xem chi tiết', url('/account/return-requests/' . $this->returnRequest->id))
            ->line('Cảm ơn bạn đã tin tưởng ' . config('app.name'));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'Bạn vừa tạo yêu cầu RMA #' . $this->returnRequest->id,
            'rr_id' => $this->returnRequest->id,
        ];
    }
}
