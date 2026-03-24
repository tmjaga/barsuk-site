<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class NewsletterHtmlMail extends Mailable
{
    public function __construct(public $subject, public $html) {}

    public function build(): self
    {
        return $this->subject($this->subject)->html($this->html);
    }
}
