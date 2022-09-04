<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatisticsFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $batch
     */
    public function __construct(public array $batch, public string $exceptionMessage)
    {
        //
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
            ->line('Generating statistics failed!')
            ->line(sprintf('Error message: %s', $this->exceptionMessage));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(AnonymousNotifiable|User $notifiable): array
    {
        return [
            //
        ];
    }
}
