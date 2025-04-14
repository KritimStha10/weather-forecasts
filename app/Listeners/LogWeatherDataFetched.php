<?php

namespace App\Listeners;

use App\Events\WeatherDataFetched;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class LogWeatherDataFetched
{
    public function handle(WeatherDataFetched $event)
    {
        Log::info("Weather data fetched for date: {$event->date}");
    }
}