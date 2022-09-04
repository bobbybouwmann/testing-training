<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Bobby',
            'email' => 'bobby@testing.com',
        ]);

        User::factory(5)
            ->has(
                Location::factory(3)
                    ->has(
                        Statistic::factory(5)
                    )
            )
            ->create();
    }
}
