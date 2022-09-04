<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Statistic>
 */
class StatisticFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location_id' => fn () => Location::factory()->create()->id,
            'date' => $this->faker->date(),
            'number_of_residents' => $this->faker->numberBetween(100.000, 999.999),
            'number_of_houses' => $this->faker->numberBetween(10.000, 99.999),
            'number_of_cinemas' => $this->faker->numberBetween(10, 999),
        ];
    }
}
