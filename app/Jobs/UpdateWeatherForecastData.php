<?php

namespace App\Jobs;

use App\Services\WeatherApi\WeatherException;
use App\Services\WeatherForecastData;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateWeatherForecastData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(protected WeatherForecastData $weatherForecastData)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     * @throws WeatherException
     */
    public function handle()
    {
        $this->weatherForecastData->update();
    }
}
