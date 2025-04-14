<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Jobs\FetchWeatherData;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new FetchWeatherData())->dailyAt('00:00');
        $schedule->job(new FetchWeatherData())->dailyAt('06:00');
        $schedule->job(new FetchWeatherData())->dailyAt('12:00');
        $schedule->job(new FetchWeatherData())->dailyAt('18:00');
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}