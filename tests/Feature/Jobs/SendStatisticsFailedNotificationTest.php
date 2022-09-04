<?php

namespace Tests\Feature\Jobs;

use App\Jobs\SendStatisticsFailedNotification;
use App\Notifications\StatisticsFailedNotification;
use Exception;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendStatisticsFailedNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function test_send_statistics_failed_notification(): void
    {
        // Arrange
        $batch = [
            'processedJobs' => 5,
        ];
        $exception = new Exception('Whoops, kappuuuttt');

        // Act
        dispatch(new SendStatisticsFailedNotification($batch, $exception->getMessage()));

        // Assert
        Notification::assertCount(1);

        Notification::assertSentTo(
            new AnonymousNotifiable(),
            StatisticsFailedNotification::class,
            function ($notification, $channels, $notifiable) {
                $mail = $notification->toMail($notifiable);

                return in_array('bobby@testing.com', $notifiable->routes['mail'])
                    && in_array('Error message: Whoops, kappuuuttt', $mail->introLines);
            }
        );
    }
}
