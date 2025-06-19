<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
  public $contact;
    public $replyContent;

    public function __construct($contact, $replyContent)
    {
        $this->contact = $contact;
        // $this->replyContent = $replyContent;
    }

    public function build()
    {
        return $this->subject('Cảm ơn bạn đã gửi phản hồi chúng tôi sẽ sớm liên hệ với bạn !')
                    ->view('emails.contact_reply');
    }
}
