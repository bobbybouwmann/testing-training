<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StatisticsReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  array<string, mixed>  $batch
     */
    public function __construct(public array $batch)
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
            ->line('Statistics are ready!')
            ->line(sprintf('%d jobs processed', $this->batch['processedJobs']))
            ->action('Check the app', url('/'));
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
