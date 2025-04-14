<?php

use App\Http\Controllers\Api\WeatherForecastController;
use Illuminate\Support\Facades\Route;

Route::middleware('api')->group(function () {
    Route::get('forecasts', [WeatherForecastController::class, 'index'])->name('forecasts.index');
    
    Route::post('forecasts', [WeatherForecastController::class, 'store'])->name('forecasts.store');
    
    Route::get('forecasts/{id}', [WeatherForecastController::class, 'show'])->name('forecasts.show');
    
    Route::put('forecasts/{id}', [WeatherForecastController::class, 'update'])->name('forecasts.update');
    
    Route::delete('forecasts/{id}', [WeatherForecastController::class, 'destroy'])->name('forecasts.destroy');
    
    Route::get('forecasts/date/{date}', [WeatherForecastController::class, 'forecastsByDate'])->name('forecasts.date');
});
