<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CampaignMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subjectText;
    public $bodyHtml;

    public function __construct($subject, $body)
    {
        $this->subjectText = $subject;
        $this->bodyHtml = $body;
    }

    public function build()
    {
        return $this->subject($this->subjectText)
            ->html($this->bodyHtml); // gửi HTML không mã hóa
    }
}