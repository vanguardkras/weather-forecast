<?php

namespace App\Http\Resources;

use App\Models\WeatherForecast;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin WeatherForecast
 */
class WeatherForecastResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'city' => $this->when($this->relationLoaded('city'), $this->city->id),
            'temperature_max' => $this->temperature_max,
            'temperature_min' => $this->temperature_min,
            'temperature_avg' => $this->temperature_avg,
            'humidity' => $this->humidity,
            'condition' => $this->condition,
        ];
    }
}
