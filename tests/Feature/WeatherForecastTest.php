<?php

namespace Tests\Feature;

use App\Jobs\FetchWeatherData;
use App\Models\WeatherForecast;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class WeatherForecastTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_forecast_for_date()
    {
        $date = Carbon::today()->format('Y-m-d');
        
        WeatherForecast::factory()->create([
            'date' => $date,
            'location' => 'New York,US',
            'temperature' => 22.5
        ]);

        $response = $this->getJson("/api/forecasts/date/{$date}");

        $response->assertStatus(200)
            ->assertJsonCount(1)
            ->assertJsonFragment(['location' => 'New York,US']);
    }

    public function test_job_dispatched_when_data_not_available()
    {
        Queue::fake();

        $date = Carbon::tomorrow()->format('Y-m-d');
        $response = $this->getJson("/api/forecasts/date/{$date}");

        Queue::assertPushed(FetchWeatherData::class);
        $response->assertStatus(202);
    }

    public function test_crud_operations()
    {
        // Test Create
        $response = $this->postJson('/api/forecasts', [
            'date' => '2023-06-15',
            'location' => 'Test City',
            'temperature' => 25.5,
            'humidity' => 60,
            'weather_description' => 'Sunny'
        ]);
        $response->assertStatus(201);

        // Test Read
        $id = $response->json('id');
        $this->getJson("/api/forecasts/{$id}")->assertStatus(200);

        // Test Update
        $this->putJson("/api/forecasts/{$id}", ['temperature' => 26.0])
            ->assertStatus(200)
            ->assertJson(['temperature' => 26.0]);

        // Test Delete
        $this->deleteJson("/api/forecasts/{$id}")->assertStatus(204);
    }
}