<?php

namespace App\Console\Commands;

use App\Jobs\CalculateLocationStatistics;
use App\Jobs\SendStatisticsFailedNotification;
use App\Jobs\SendStatisticsReadyNotification;
use App\Models\Location;
use Illuminate\Bus\Batch;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Bus;
use Throwable;

class GenerateLocationStatistics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'testing:generate-location-statistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Statistics for each Location';

    public function handle(): int
    {
        $jobs = Location::all()->map(function (Location $location) {
            return new CalculateLocationStatistics($location);
        });

        if ($jobs->isEmpty()) {
            return self::SUCCESS;
        }

        $batch = Bus::batch($jobs)
            ->then(function (Batch $batch) {
                dispatch(new SendStatisticsReadyNotification($batch->toArray()));
            })->catch(function (Batch $batch, Throwable $exception) {
                dispatch(new SendStatisticsFailedNotification($batch->toArray(), $exception->getMessage()));
            })->finally(function (Batch $batch) {
            })->dispatch();

        $this->info(sprintf('Calculated statistics for %d locations', $batch->totalJobs));

        return self::SUCCESS;
    }
}
