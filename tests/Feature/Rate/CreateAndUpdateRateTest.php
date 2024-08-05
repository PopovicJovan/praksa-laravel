<?php

namespace Tests\Feature\Rate;

use App\Models\Movie;
use App\Models\User;
use Tests\TestCase;

class CreateAndUpdateRateTest extends TestCase
{
    public function test_post_rate_without_auth(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->post('/api/movie/1/rate?rate=2');

        $response->assertStatus(401);
    }

    public function test_post_rate_bad_movie()
    {
        $email = User::inRandomOrder()->first()->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post('/api/movie/1/rate?rate=2');

        $response->assertStatus(404);
    }

    public function test_post_rate_bad_rate()
    {
        $email = User::inRandomOrder()->first()->email;
        $movie = Movie::inRandomOrder()->first()->id;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie/rate?rate=7");

        $response->assertStatus(422);
    }

    public function test_create_rate()
    {
        $email = User::inRandomOrder()->first()->email;
        $movie = Movie::inRandomOrder()->first()->id;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie/rate?rate=4");

        $response->assertStatus(200);
    }
}
