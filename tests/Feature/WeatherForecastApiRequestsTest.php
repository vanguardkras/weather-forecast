<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\WeatherForecast;
use App\Services\WeatherApi\Weather;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
use Tests\TestCase;

class WeatherForecastApiRequestsTest extends TestCase
{
    use RefreshDatabase;

    public function testIndex()
    {
        $this->mock(Weather::class, function (MockInterface $mock) {
            $mock->shouldReceive('getForecast')
                ->andReturn([]);
        });

        City::factory()->count(3)->create();
        WeatherForecast::factory()->createOne(['city_id' => 1, 'date' => '2022-01-01']);
        WeatherForecast::factory()->createOne(['city_id' => 2, 'date' => '2022-01-01']);
        WeatherForecast::factory()->createOne(['city_id' => 3, 'date' => '2022-01-01']);

        $this->getJson('/api/weather_forecast?date=2022-01-02')
            ->assertStatus(404)
            ->assertSee('No results available');

        $this->getJson('/api/weather_forecast?date=2022-01-01')
            ->assertOk()
            ->assertJsonCount(3, 'data');
    }

    public function testStore()
    {
        City::factory()->createOne();

        $this->post('/api/weather_forecast', [
            'date' => '2022-01-01',
            'city_id' => 1,
            'temperature_max' => 11,
            'temperature_min' => 22,
            'temperature_avg' => 16,
            'humidity' => 55,
            'condition' => 'Windy',
        ])->assertStatus(201);

        $this->assertDatabaseCount('weather_forecasts', 1);
    }

    public function testShow()
    {
        City::factory()->createOne();
        WeatherForecast::factory()->createOne();

        $this->get('/api/weather_forecast/1')->assertOk();
    }

    public function testUpdate()
    {
        City::factory()->createOne();
        WeatherForecast::factory()->createOne(['city_id' => 1, 'date' => '2022-01-01']);

        $this->put('/api/weather_forecast/1', [
            'temperature_max' => 11,
            'temperature_min' => 22,
            'temperature_avg' => 16,
            'humidity' => 55,
            'condition' => 'Windy',
        ])->assertStatus(200);

        $this->assertDatabaseHas('weather_forecasts', [
            'city_id' => 1,
            'temperature_max' => 11,
            'temperature_min' => 22,
            'temperature_avg' => 16,
            'humidity' => 55,
            'condition' => 'Windy',
        ]);
        $this->assertDatabaseCount('weather_forecasts', 1);
    }

    public function testDestroy()
    {
        City::factory()->createOne();
        WeatherForecast::factory()->createOne();

        $this->delete('/api/weather_forecast/1')->assertOk();

        $this->assertDatabaseCount('weather_forecasts', 0);
    }
}
