<?php

namespace App\Services\WeatherApi;

use Illuminate\Support\Facades\Http;

class Weather
{
    /**
     * WeatherApi api key
     */
    protected string $apiKey;

    /**
     * WeatherApi api address
     */
    protected string $apiUrl = 'https://api.weatherapi.com/v1/forecast.json';

    /**
     * Maximum number of days
     */
    protected int $maxDays = 3;

    /**
     * Values units
     */
    protected string $units = 'metric';

    public function __construct()
    {
        $this->apiKey = config('services.weather_api.api_key');
    }

    /**
     * Get current weather data
     *
     * @param string $city
     * @return array
     * @throws WeatherException
     */
    public function getForecast(string $city): array
    {
        $result = $this->requestData([
            'q' => $city,
            'days' => $this->maxDays,
        ]);

        return $result['forecast']['forecastday'] ?? [];
    }

    /**
     * Request data from WeatherApi
     *
     * @param array $query
     * @return array
     * @throws WeatherException
     */
    protected function requestData(array $query = []): array
    {
        $query['key'] = $this->apiKey;
        $query['aqi'] = 'no';
        $query['alerts'] = 'no';

        $response = Http::get($this->apiUrl, $query);

        if (!$response->ok()) {
            $message = json_encode($response->json());
            $status = $response->status();
            throw new WeatherException("Response message: $message, Status: $status");
        }

        return $response->json();
    }
}
