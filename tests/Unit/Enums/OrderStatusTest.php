<?php

namespace Tests\Unit\Enums;

use App\Enums\OrderStatus;
use PHPUnit\Framework\TestCase;

class OrderStatusTest extends TestCase
{
    /**
     * @dataProvider isPendingStatuses
     */
    public function test_order_status_is_pending(string $status, bool $expected): void
    {
        // Act & Assert
        $this->assertEquals($expected, OrderStatus::isPending($status));
    }

    /**
     * @dataProvider isPaidStatuses
     */
    public function test_order_status_is_paid(string $status, bool $expected): void
    {
        // Act & Assert
        $this->assertEquals($expected, OrderStatus::isPaid($status));
    }

    /**
     * @dataProvider isFailedStatuses
     */
    public function test_order_status_is_failed(string $status, bool $expected): void
    {
        // Act & Assert
        $this->assertEquals($expected, OrderStatus::isFailed($status));
    }

    public function isPendingStatuses(): array
    {
        return [
            'initiated' => [
                'status' => 'initiated',
                'expected' => true,
            ],
            'pending' => [
                'status' => 'pending',
                'expected' => true,
            ],
            'paid' => [
                'status' => 'paid',
                'expected' => false,
            ],
            'cancelled' => [
                'status' => 'cancelled',
                'expected' => false,
            ],
            'failed' => [
                'status' => 'failed',
                'expected' => false,
            ],
        ];
    }

    public function isPaidStatuses(): array
    {
        return [
            'initiated' => [
                'status' => 'initiated',
                'expected' => false,
            ],
            'pending' => [
                'status' => 'pending',
                'expected' => false,
            ],
            'paid' => [
                'status' => 'paid',
                'expected' => true,
            ],
            'cancelled' => [
                'status' => 'cancelled',
                'expected' => false,
            ],
            'failed' => [
                'status' => 'failed',
                'expected' => false,
            ],
        ];
    }

    public function isFailedStatuses(): array
    {
        return [
            'initiated' => [
                'status' => 'initiated',
                'expected' => false,
            ],
            'pending' => [
                'status' => 'pending',
                'expected' => false,
            ],
            'paid' => [
                'status' => 'paid',
                'expected' => false,
            ],
            'cancelled' => [
                'status' => 'cancelled',
                'expected' => true,
            ],
            'failed' => [
                'status' => 'failed',
                'expected' => true,
            ],
        ];
    }
}
