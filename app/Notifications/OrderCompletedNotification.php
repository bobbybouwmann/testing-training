<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCompletedNotification extends Notification
{
    use Queueable;

    public function __construct(public Order $order)
    {
    }

    /**
     * @return string[]
     */
    public function via(AnonymousNotifiable|User $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(AnonymousNotifiable|User $notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('Order Completed!')
            ->line(sprintf('You paid € %s', $this->order->total / 100));
    }

    /**
     * @return string[]
     */
    public function toArray(AnonymousNotifiable|User $notifiable): array
    {
        return [
            //
        ];
    }
}
