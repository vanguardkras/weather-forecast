<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $cityId
 * @property float $temperature_max
 * @property float $temperature_min
 * @property float $temperature_avg
 * @property int $humidity
 * @property string $condition
 * @property Carbon $date
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property City $city
 */
class WeatherForecast extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'city_id' => 'integer',
        'temperature_max' => 'float',
        'temperature_min' => 'float',
        'temperature_avg' => 'float',
        'humidity' => 'float',
        'date' => 'date',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'city_id',
        'temperature_max',
        'temperature_min',
        'temperature_avg',
        'humidity',
        'condition',
        'date',
    ];

    /**
     * Current weather forecast city
     */
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
