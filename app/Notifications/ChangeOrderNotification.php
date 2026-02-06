<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ChangeOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected array $data) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Booking Changed')
            ->view('emails.order-change', [
                'order' => $this->data['order'],
                'duration' => $this->data['duration'],
                'total_price' => $this->data['total_price'],
            ]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
