<?php

namespace Database\Factories;

use App\Models\City;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherForecastFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'city_id' => City::inRandomOrder()->first()->id,
            'temperature_max' => rand(0, 20),
            'temperature_min' => rand(0, 20),
            'temperature_avg' => rand(0, 20),
            'humidity' => rand(0, 20),
            'condition' => $this->faker->word,
            'date' => $this->faker->date,
        ];
    }
}
