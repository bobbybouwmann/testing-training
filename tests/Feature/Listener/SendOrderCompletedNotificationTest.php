<?php

namespace Tests\Feature\Listener;

use App\Enums\OrderStatus;
use App\Events\OrderCompleted;
use App\Listeners\SendOrderCompletedNotification;
use App\Models\Order;
use App\Notifications\OrderCompletedNotification;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class SendOrderCompletedNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Notification::fake();
    }

    public function test_order_completed_notification(): void
    {
        // Arrange
        $order = Order::factory()->create([
            'status' => OrderStatus::PAID,
        ]);

        // Act
        (new SendOrderCompletedNotification())->handle(new OrderCompleted($order));

        // Assert
        Notification::assertTimesSent(1, OrderCompletedNotification::class);
    }

    public function test_order_completed_notification_not_sent_for_incorrect_status(): void
    {
        // Arrange
        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        // Act
        (new SendOrderCompletedNotification())->handle(new OrderCompleted($order));

        // Assert
        Notification::assertNothingSent();
    }

    public function test_order_completed_notification_not_sent_without_user(): void
    {
        // Arrange
        $order = Order::factory()->create([
            'status' => OrderStatus::PAID,
            'user_id' => null,
        ]);

        // Act
        (new SendOrderCompletedNotification())->handle(new OrderCompleted($order));

        // Assert
        Notification::assertNothingSent();
    }
}
