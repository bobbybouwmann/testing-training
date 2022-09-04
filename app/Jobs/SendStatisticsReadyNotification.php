<?php

namespace App\Jobs;

use App\Notifications\StatisticsReadyNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Notification;

class SendStatisticsReadyNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param  array<string, mixed>  $batch
     */
    public function __construct(public array $batch)
    {
        //
    }

    public function handle(): void
    {
        Notification::route('mail', ['bobby@testing.com'])
            ->notify(new StatisticsReadyNotification($this->batch));
    }
}
