<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class WeatherDataFetched
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $date;
    public $forecasts;

    public function __construct($date, $forecasts)
    {
        $this->date = $date;
        $this->forecasts = $forecasts;
    }
}