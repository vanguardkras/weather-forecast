<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    protected array $initialCities = [
        'New York',
        'London',
        'Paris',
        'Berlin',
        'Tokyo',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->initialCities as $cityName) {
            City::factory()->createOne(['name' => $cityName]);
        }
    }
}
