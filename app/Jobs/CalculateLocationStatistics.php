<?php

namespace App\Jobs;

use App\Models\Location;
use Faker\Generator;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class CalculateLocationStatistics implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Location $location)
    {
    }

    public function handle(Generator $faker): void
    {
        $date = Carbon::now();

        $this->location->statistics()->updateOrCreate(
            [
                'date' => $date,
            ],
            [
                'number_of_residents' => $faker->numberBetween(100.000, 999.999),
                'number_of_houses' => $faker->numberBetween(10.000, 99.999),
                'number_of_cinemas' => $faker->numberBetween(10, 999),
            ]);
    }
}
