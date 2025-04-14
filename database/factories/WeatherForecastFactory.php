<?php

namespace Database\Factories;

use App\Models\WeatherForecast;
use Illuminate\Database\Eloquent\Factories\Factory;

class WeatherForecastFactory extends Factory
{
    protected $model = WeatherForecast::class;

    public function definition()
    {
        return [
            'date' => $this->faker->date(),
            'location' => $this->faker->city . ',' . $this->faker->countryCode,
            'temperature' => $this->faker->randomFloat(2, -10, 40),
            'humidity' => $this->faker->numberBetween(0, 100),
            'weather_description' => $this->faker->randomElement(['Sunny', 'Cloudy', 'Rainy', 'Snowy']),
            'wind_speed' => $this->faker->randomFloat(2, 0, 50),
            'pressure' => $this->faker->numberBetween(900, 1100),
        ];
    }
}