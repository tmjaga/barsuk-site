<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Pelago\Emogrifier\CssInliner;

class ContactMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected array $data) {}

    public function build(): self
    {
        $htmlMail = view('emails.new-contact', ['data' => $this->data])->render();
        $cssFile = getManifestCssFile();
        $css = file_get_contents($cssFile);
        $inlinedHtml = CssInliner::fromHtml($htmlMail)->inlineCss($css)->render();

        return $this->subject('New Contact Message: '.$this->data['subject'])->html($inlinedHtml);
    }
}
