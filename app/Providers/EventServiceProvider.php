<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\WeatherDataFetched;
use App\Listeners\LogWeatherDataFetched;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        WeatherDataFetched::class => [
            LogWeatherDataFetched::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}