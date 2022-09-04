<?php

namespace Tests\Unit\Dto;

use App\Dto\Progress;
use PHPUnit\Framework\TestCase;

final class ProgressTest extends TestCase
{
    /**
     * @dataProvider progressData
     */
    public function test_can_calculate_correct_amount(int $total, int $current, int $expected): void
    {
        // Arrange & Act
        $progress = new Progress($total, $current);

        // Assert
        $this->assertEquals($expected, $progress->percentage());
    }

    public function progressData(): array
    {
        return [
            '0' => [
                'total' => 0,
                'current' => 0,
                'expected' => 0,
            ],
            '10' => [
                'total' => 10,
                'current' => 1,
                'expected' => 10,
            ],
            '33' => [
                'total' => 3,
                'current' => 1,
                'expected' => 33,
            ],
            '62' => [
                'total' => 13,
                'current' => 8,
                'expected' => 62,
            ],
            '67' => [
                'total' => 3,
                'current' => 2,
                'expected' => 67,
            ],
            '100' => [
                'total' => 4,
                'current' => 4,
                'expected' => 100,
            ],
            '150' => [
                'total' => 2,
                'current' => 3,
                'expected' => 100,
            ],
        ];
    }
}
