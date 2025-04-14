<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeatherForecast;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use App\Jobs\FetchWeatherData;
use Illuminate\Support\Facades\Cache;

class WeatherForecastController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        return WeatherForecast::all();
    }

    public function show($date)
    {
        try {
            $date = Carbon::createFromFormat('Y-m-d', $date)->format('Y-m-d');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid date format. Use YYYY-MM-DD'], 400);
        }

        $cacheKey = "weather_forecast_{$date}";
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $forecasts = WeatherForecast::where('date', $date)->get();

        if ($forecasts->isEmpty()) {
            FetchWeatherData::dispatch($date);

            return response()->json([
                'message' => 'Data not available. Fetching from API. Please try again shortly.'
            ], 202);
        }

        Cache::put($cacheKey, $forecasts, now()->addHour());

        return response()->json($forecasts);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'location' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|integer',
            'weather_description' => 'required|string',
            'wind_speed' => 'nullable|numeric',
            'pressure' => 'nullable|integer',
            'weather_condition' => 'required|string',
        ]);

        $forecast = WeatherForecast::create($validated);

        return response()->json($forecast, 201);
    }

    public function update(Request $request, $id)
    {
        $forecast = WeatherForecast::findOrFail($id);

        $validated = $request->validate([
            'date' => 'required|date',
            'location' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|integer',
            'weather_description' => 'required|string',
            'wind_speed' => 'nullable|numeric',
            'pressure' => 'nullable|integer',
            'weather_condition' => 'required|string',
        ]);

        $forecast->update($validated);

        return response()->json($forecast);
    }

    public function destroy($id)
    {
        WeatherForecast::findOrFail($id)->delete();

        return response()->json(null, 204);
    }
}