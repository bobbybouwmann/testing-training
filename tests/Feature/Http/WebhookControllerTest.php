<?php

namespace Tests\Feature\Http;

use App\Enums\OrderStatus;
use App\Enums\WebhookType;
use App\Events\CreateNewUserRequested;
use App\Events\OrderCancelled;
use App\Events\OrderPaid;
use App\Models\Order;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;
use Tests\TestCase;

class WebhookControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Event::fake([
            OrderPaid::class,
            OrderCancelled::class,
            CreateNewUserRequested::class,
        ]);
    }

    public function test_webhook_controller_order_paid(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        $order = Order::factory()->create([
            'status' => OrderStatus::PENDING,
        ]);

        // Act
        $response = $this->post('webhook', [
            'token' => $token,
            'type' => WebhookType::ORDER_PAID,
            'order_id' => $order->id,
        ]);

        // Assert
        $response->assertOk();

        Event::assertDispatched(OrderPaid::class);
    }

    public function test_webhook_controller_order_cancelled(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        $order = Order::factory()->create([
            'status' => OrderStatus::PAID,
        ]);

        // Act
        $response = $this->post('webhook', [
            'token' => $token,
            'type' => WebhookType::ORDER_CANCELLED,
            'order_id' => $order->id,
        ]);

        // Assert
        $response->assertOk();

        Event::assertDispatched(OrderCancelled::class);
        Event::assertNotDispatched(OrderPaid::class);
    }

    public function test_webhook_controller_create_new_user_requested(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        // Act
        $response = $this->post('webhook', [
            'token' => $token,
            'type' => WebhookType::CREATE_CUSTOMER,
            'customer' => [
                'name' => 'John Doe',
                'email' => 'johndoe@mail.com',
            ],
        ]);

        // Assert
        $response->assertOk();

        Event::assertDispatched(CreateNewUserRequested::class);
    }

    public function test_webhook_controller_missing_token(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        // Act
        $response = $this->post('webhook');

        // Assert
        $response->assertUnauthorized();
        $response->assertSee('Invalid token');

        Event::assertNothingDispatched();
    }

    public function test_webhook_controller_invalid_token(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        // Act
        $response = $this->post('webhook', [
            'token' => Str::random(),
        ]);

        // Assert
        $response->assertUnauthorized();
        $response->assertSee('Invalid token');

        Event::assertNothingDispatched();
    }

    public function test_webhook_controller_invalid_type(): void
    {
        // Arrange
        $token = Str::random();
        config()->set('app.token', $token);

        // Act
        $response = $this->post('webhook', [
            'token' => $token,
            'type' => 'random-type',
        ]);

        // Assert
        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertSee('Invalid type');

        Event::assertNothingDispatched();
    }
}
