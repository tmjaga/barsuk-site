<?php

namespace App\Notifications;

use App\Models\Mailing;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewsletterNotification extends Notification
{
    public function __construct(protected Mailing $mailing, protected string $mailBody) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->mailing->subject)
            ->view('emails.newsletter', [
                'mailSubject' => $this->mailing->subject,
                'mailBody' => $this->mailBody,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
