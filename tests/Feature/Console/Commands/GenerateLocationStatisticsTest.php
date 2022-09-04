<?php

namespace Tests\Feature\Console\Commands;

use App\Console\Commands\GenerateLocationStatistics;
use App\Models\Location;
use App\Notifications\StatisticsReadyNotification;
use Illuminate\Bus\PendingBatch;
use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class GenerateLocationStatisticsTest extends TestCase
{
    public function test_generate_locations_statistics(): void
    {
        // Arrange
        Notification::fake();

        $locations = Location::factory()->count(2)->create();

        // Act
        $this->artisan(GenerateLocationStatistics::class)
            ->assertSuccessful();

        // Assert
        foreach ($locations as $location) {
            $this->assertDatabaseHas('statistics', [
                'location_id' => $location->id,
            ]);
        }

        $this->assertDatabaseCount('statistics', 2);

        Notification::assertSentTo(new AnonymousNotifiable(), StatisticsReadyNotification::class);
    }

    public function test_generate_locations_statistics_multiple_dates(): void
    {
        // Arrange
        $dates = ['2022-11-05', '2022-12-05'];
        $locations = Location::factory()->count(2)->create();

        // Act
        foreach ($dates as $date) {
            Carbon::setTestNow($date);

            $this->artisan(GenerateLocationStatistics::class)
                ->assertSuccessful();
        }

        // Assert
        $this->assertDatabaseCount('locations', 2);
        $this->assertDatabaseCount('statistics', 4);

        foreach ($locations as $location) {
            foreach ($dates as $date) {
                $this->assertDatabaseHas('statistics', [
                    'location_id' => $location->id,
                    'date' => (string) Carbon::parse($date),
                ]);
            }
        }
    }

    public function test_generate_locations_statistics_batch_size_for_locations(): void
    {
        // Arrange
        Bus::fake();

        $locations = Location::factory()->count(5)->create();

        // Act
        $this->artisan(GenerateLocationStatistics::class)
            ->assertSuccessful();

        // Assert
        Bus::assertBatched(function (PendingBatch $batch) use ($locations) {
            return $batch->jobs->count() === $locations->count();
        });
    }

    public function test_generate_locations_statistics_without_locations(): void
    {
        // Arrange
        Bus::fake();

        // Act
        $this->artisan(GenerateLocationStatistics::class)
            ->assertSuccessful();

        // Assert
        Bus::assertBatchCount(0);
    }
}
