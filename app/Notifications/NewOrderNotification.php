<?php

namespace App\Notifications;

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
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Booking')
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

    public function toDatabase($notifiable): array
    {
        $order = $this->data['order'];

        return [
            'type'    => 'order',
            'order_id' => $order->id,
            'message' => 'New order from: ' . $order->names,
            'email'   => $order->email,
        ];
    }
}
