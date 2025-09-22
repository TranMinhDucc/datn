<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QcCompletedNotification extends Notification
{
    use Queueable;

    public $returnRequest;

    public function __construct($returnRequest)
    {
        $this->returnRequest = $returnRequest;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('✅ QC đã hoàn tất cho yêu cầu #' . $this->returnRequest->id)
            ->line('QC đã hoàn tất cho yêu cầu hoàn/đổi hàng #' . $this->returnRequest->id)
            ->action('Xem chi tiết', url('/account/return-requests/' . $this->returnRequest->id));
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'QC đã hoàn tất cho yêu cầu #' . $this->returnRequest->id,
            'rr_id' => $this->returnRequest->id,
        ];
    }
}
