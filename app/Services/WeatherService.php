<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    protected $apiKey;
    protected $baseUrl = 'https://api.openweathermap.org/data/2.5/forecast';

    public function __construct()
    {
        $this->apiKey = env('OPENWEATHERMAP_API_KEY');
    }

    public function getWeatherData($city, $date)
    {
        try {
            $response = Http::get($this->baseUrl, [
                'q' => $city,
                'appid' => $this->apiKey,
                'units' => 'metric',
                'dt' => strtotime($date),
            ]);

            if ($response->successful()) {
                return $this->transformWeatherData($response->json(), $city, $date);
            }

            Log::error('Weather API error: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Weather API exception: ' . $e->getMessage());
            return null;
        }
    }

    protected function transformWeatherData($data, $city, $date)
    {
        return [
            'date' => $date,
            'location' => $city,
            'temperature' => $data['main']['temp'],
            'humidity' => $data['main']['humidity'],
            'weather_description' => $data['weather'][0]['description'],
            'wind_speed' => $data['wind']['speed'],
            'pressure' => $data['main']['pressure'],
        ];
    }
}