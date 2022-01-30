<?php

namespace Tests\Feature;

use App\Models\City;
use App\Services\WeatherApi\Weather;
use App\Services\WeatherForecastData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class WeatherForecastApiTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdate()
    {
        $this->mock(Weather::class, function (MockInterface $mock) {
            $mock->shouldReceive('getForecast')
                ->andReturn([
                    [
                        'date' => '2022-01-01',
                        'day' => [
                            'maxtemp_c' => 10,
                            'mintemp_c' => 20,
                            'avgtemp_c' => 15,
                            'avghumidity' => 50,
                            'condition' => ['text' => 'Sunny'],
                        ],
                    ],
                    [
                        'date' => '2022-01-02',
                        'day' => [
                            'maxtemp_c' => 12,
                            'mintemp_c' => 22,
                            'avgtemp_c' => 17,
                            'avghumidity' => 60,
                            'condition' => ['text' => 'Cloudy'],
                        ],
                    ],
                ]);
        });

        $weatherForecastData = app()->make(WeatherForecastData::class);

        $weatherForecastData->update();

        $this->assertDatabaseCount('cities', 0);
        $this->assertDatabaseCount('weather_forecasts', 0);

        City::factory()->createOne(['name' => 'TestCity1']);
        City::factory()->createOne(['name' => 'TestCity2']);

        $weatherForecastData->update();

        $this->assertDatabaseCount('cities', 2);
        $this->assertDatabaseCount('weather_forecasts', 4);

        $this->assertDatabaseHas('weather_forecasts', [
            'city_id' => 1,
            'date' => '2022-01-01 00:00:00',
            'temperature_max' => 10,
            'temperature_min' => 20,
            'temperature_avg' => 15,
            'humidity' => 50,
            'condition' => 'Sunny',
        ]);

        $this->assertDatabaseHas('weather_forecasts', [
            'city_id' => 1,
            'date' => '2022-01-02 00:00:00',
            'temperature_max' => 12,
            'temperature_min' => 22,
            'temperature_avg' => 17,
            'humidity' => 60,
            'condition' => 'Cloudy',
        ]);

        $this->assertDatabaseHas('weather_forecasts', [
            'city_id' => 2,
            'date' => '2022-01-01 00:00:00',
            'temperature_max' => 10,
            'temperature_min' => 20,
            'temperature_avg' => 15,
            'humidity' => 50,
            'condition' => 'Sunny',
        ]);

        $this->assertDatabaseHas('weather_forecasts', [
            'city_id' => 2,
            'date' => '2022-01-02 00:00:00',
            'temperature_max' => 12,
            'temperature_min' => 22,
            'temperature_avg' => 17,
            'humidity' => 60,
            'condition' => 'Cloudy',
        ]);
    }


}
