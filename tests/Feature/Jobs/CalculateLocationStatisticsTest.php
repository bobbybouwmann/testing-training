<?php

namespace Tests\Feature\Jobs;

use App\Jobs\CalculateLocationStatistics;
use App\Models\Location;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CalculateLocationStatisticsTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2022, 9, 9));
    }

    public function test_calculate_statistics_for_location(): void
    {
        // Arrange
        $location = Location::factory()->create();

        // Act
        dispatch(new CalculateLocationStatistics($location));

        // Assert
        $this->assertDatabaseHas('statistics', [
            'date' => '2022-09-09',
        ]);
    }
}
