<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendStatisticsReadyNotification;
use App\Notifications\StatisticsReadyNotification;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendStatisticsReadyNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function test_send_statistics_ready_notification(): void
    {
        // Arrange
        $batch = [
            'processedJobs' => 5,
        ];

        // Act
        dispatch(new SendStatisticsReadyNotification($batch));

        // Assert
        Notification::assertCount(1);

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            StatisticsReadyNotification::class,
            function ($notification, $channels, $notifiable) {
                $mail = $notification->toMail($notifiable);

                return in_array('bobby@testing.com', $notifiable->routes['mail'])
                    && in_array('5 jobs processed', $mail->introLines);
            }
        );
    }
}
