<?php

namespace App\Mail;

use App\Models\SupportTicket;
use App\Models\SupportTicketMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportTicketReplied extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public SupportTicket $ticket,
        public SupportTicketMessage $message
    ) {}

    public function build()
    {
        return $this->subject('Phản hồi mới cho phiếu #' . $this->ticket->id . ' – ' . $this->ticket->subject)
                    ->markdown('emails.support.ticket_replied');
    }
}
