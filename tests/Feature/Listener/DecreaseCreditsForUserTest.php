<?php

namespace Tests\Feature\Listener;

use App\Enums\OrderStatus;
use App\Events\OrderCompleted;
use App\Listeners\DecreaseCreditsForUser;
use App\Models\Order;
use App\Models\User;
use Tests\TestCase;

class DecreaseCreditsForUserTest extends TestCase
{
    public function test_order_completed_decrease_credits(): void
    {
        // Arrange
        $user = User::factory()->create([
            'credits' => 100,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user,
            'status' => OrderStatus::PAID,
            'total' => 50,
        ]);

        // Act
        (new DecreaseCreditsForUser())->handle(new OrderCompleted($order));

        // Assert
        $this->assertEquals(50, $user->fresh()->credits);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'credits' => 50,
        ]);
    }

    public function test_order_completed_decrease_credits_too_much_credits(): void
    {
        // Arrange
        $user = User::factory()->create([
            'credits' => 100,
        ]);

        $order = Order::factory()->create([
            'user_id' => $user,
            'status' => OrderStatus::PAID,
            'total' => 300,
        ]);

        // Act
        (new DecreaseCreditsForUser())->handle(new OrderCompleted($order));

        // Assert
        $this->assertEquals(0, $user->fresh()->credits);
    }

    public function test_order_completed_decrease_credits_no_user(): void
    {
        // Arrange
        $order = Order::factory()->create([
            'user_id' => null,
            'status' => OrderStatus::PAID,
            'total' => 50,
        ]);

        // Act
        (new DecreaseCreditsForUser())->handle(new OrderCompleted($order));

        // Assert
        $this->assertDatabaseCount('users', 0);
    }
}
