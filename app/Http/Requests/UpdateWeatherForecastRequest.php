<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWeatherForecastRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'temperature_max' => 'required|numeric',
            'temperature_min' => 'required|numeric',
            'temperature_avg' => 'required|numeric',
            'humidity' => 'required|numeric',
            'condition' => 'required|string',
        ];
    }
}
