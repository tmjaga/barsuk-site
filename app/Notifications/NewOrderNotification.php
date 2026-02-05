<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewOrderNotification extends Notification implements ShouldQueue
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
            ->subject('New Booking Created')
            ->view('emails.new-booking', [
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
