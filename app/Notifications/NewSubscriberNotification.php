<?php

namespace App\Notifications;

use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewSubscriberNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Subscriber $subscriber) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Sibscriber')
            ->view('emails.new-subscriber', [
                'subscriber' => $this->subscriber,
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
