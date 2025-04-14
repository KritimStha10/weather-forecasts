<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\WeatherForecast;
use App\Services\WeatherService;
use App\Events\WeatherDataFetched;
use Carbon\Carbon;

class FetchWeatherData implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $date;
    protected $locations = [
        'New York,US',
        'London,GB',
        'Paris,FR',
        'Berlin,DE',
        'Tokyo,JP'
    ];

    public function __construct($date = null)
    {
        $this->date = $date ?: Carbon::today()->format('Y-m-d');
    }

    public function handle(WeatherService $weatherService)
    {
        $results = [];
        
        foreach ($this->locations as $location) {
            $data = $weatherService->getWeatherData($location, $this->date);
            
            if ($data) {
                $forecast = WeatherForecast::updateOrCreate(
                    ['date' => $this->date, 'location' => $location],
                    $data
                );
                
                $results[] = $forecast;
            }
        }

        if (!empty($results)) {
            event(new WeatherDataFetched($this->date, $results));
        }
    }
}