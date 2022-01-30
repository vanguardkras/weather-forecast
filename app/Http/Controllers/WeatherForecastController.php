<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateWeatherForecastRequest;
use App\Http\Requests\GetWeatherForecastRequest;
use App\Http\Requests\UpdateWeatherForecastRequest;
use App\Http\Resources\WeatherForecastResource;
use App\Models\WeatherForecast;
use App\Services\WeatherApi\WeatherException;
use App\Services\WeatherForecastData;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class WeatherForecastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response|AnonymousResourceCollection
     * @throws WeatherException
     */
    public function index(GetWeatherForecastRequest $request, WeatherForecastData $weatherForecastData)
    {
        $date = Carbon::parse($request->date);

        $results = $weatherForecastData->getDayData($date);

        if (!$results->count()) {
            return response('No results available', 404);
        }

        return WeatherForecastResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateWeatherForecastRequest $request
     * @return WeatherForecastResource
     */
    public function store(CreateWeatherForecastRequest $request)
    {
        $weatherForecast = WeatherForecast::create($request->all());

        return new WeatherForecastResource($weatherForecast);
    }

    /**
     * Display the specified resource.
     *
     * @param WeatherForecast $weatherForecast
     * @return WeatherForecastResource
     */
    public function show(WeatherForecast $weatherForecast)
    {
        return new WeatherForecastResource($weatherForecast);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWeatherForecastRequest $request
     * @param WeatherForecast $weatherForecast
     * @return WeatherForecastResource
     */
    public function update(UpdateWeatherForecastRequest $request, WeatherForecast $weatherForecast)
    {
        $weatherForecast->update($request->all());

        return new WeatherForecastResource($weatherForecast);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param WeatherForecast $weatherForecast
     * @return Response
     */
    public function destroy(WeatherForecast $weatherForecast)
    {
        $weatherForecast->delete();

        return response('OK');
    }
}
