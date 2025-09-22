<?php

namespace App\Mail;

use App\Models\SupportTicket;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketStatusChanged extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public string $oldLabel,
        public string $newLabel
    ) {}

    public function build()
    {
        return $this->subject('Cập nhật trạng thái phiếu #' . $this->ticket->id . ' – ' . $this->ticket->subject)
                    ->markdown('emails.support.ticket_status_changed');
    }
}
