<?php

declare(strict_types=1);

namespace Tests\Unit\Traits;

use App\Traits\ConstantArrayable;
use Illuminate\Validation\Rules\In;
use PHPUnit\Framework\TestCase;

final class ConstantArrayableTest extends TestCase
{
    private mixed $class;

    public function setUp(): void
    {
        parent::setUp();

        $this->class = new class()
        {
            public const FOO = 'foo';

            public const BAR = 'bar';

            use ConstantArrayable;
        };
    }

    public function test_constant_arrayable(): void
    {
        // Arrange
        $expected = [
            'FOO' => 'foo',
            'BAR' => 'bar',
        ];

        // Act & Assert
        $this->assertEquals($expected, $this->class::toArray());
    }

    public function test_constant_arrayable_to_collection(): void
    {
        // Arrange
        $expected = collect([
            'FOO' => 'foo',
            'BAR' => 'bar',
        ]);

        // Act & Assert
        $this->assertEquals($expected, $this->class::toCollection());
        $this->assertCount(2, $this->class::toCollection());
    }

    public function test_constant_arrayable_values(): void
    {
        // Arrange
        $expected = [
            'foo',
            'bar',
        ];

        // Act & Assert
        $this->assertEquals($expected, $this->class::values());
    }

    public function test_constant_arrayable_in_rule(): void
    {
        // Act
        $rule = $this->class::rule();

        // Assert
        $this->assertInstanceOf(In::class, $rule);
    }
}
