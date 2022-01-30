<?php

namespace App\Services;

use App\Events\WeatherForecastUpdated;
use App\Models\City;
use App\Models\WeatherForecast;
use App\Services\WeatherApi\Weather;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class WeatherForecastData
{
    public function __construct(protected Weather $weather)
    {
    }

    /**
     * Get data for the current day
     *
     * @param Carbon $date
     * @return Collection
     * @throws WeatherApi\WeatherException
     */
    public function getDayData(Carbon $date): Collection
    {
        $data = $this->queryData($date);

        if ($data->count()) {
            return $data;
        }

        $this->update();

        return $this->queryData($date);
    }

    /**
     * Update database data
     *
     * @throws WeatherApi\WeatherException
     */
    public function update(): void
    {
        $cities = City::all();

        /** @var City $city */
        foreach ($cities as $city) {
            $forecasts = $this->weather->getForecast($city->name);

            foreach ($forecasts as $forecast) {
                WeatherForecast::updateOrCreate(
                    [
                        'date' => $forecast['date'],
                        'city_id' => $city->id,
                    ],
                    [
                        'temperature_max' => $forecast['day']['maxtemp_c'],
                        'temperature_min' => $forecast['day']['mintemp_c'],
                        'temperature_avg' => $forecast['day']['avgtemp_c'],
                        'humidity' => $forecast['day']['avghumidity'],
                        'condition' => $forecast['day']['condition']['text'],
                    ]
                );
            }
        }

        WeatherForecastUpdated::dispatch(now()->toDateString());
    }

    /**
     * Query data
     *
     * @param Carbon $date
     * @return Collection
     */
    protected function queryData(Carbon $date): Collection
    {
        return WeatherForecast::with('city')
            ->where('date', $date)
            ->get();
    }
}
