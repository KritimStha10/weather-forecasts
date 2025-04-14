<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeatherForecast extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'location',
        'temperature',
        'humidity',
        'wind_speed',
        'weather_condition',
        'api_raw_data'
    ];

    protected $casts = [
        'date' => 'date',
        'api_raw_data' => 'json'
    ];
}
