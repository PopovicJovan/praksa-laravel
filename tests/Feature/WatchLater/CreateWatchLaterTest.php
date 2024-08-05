<?php

namespace Tests\Feature\WatchLater;

use App\Models\Movie;
use App\Models\User;
use Tests\TestCase;

class CreateWatchLaterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_create_watch_later(): void
    {
        $user = User::inRandomOrder()->first();
        $movie = Movie::inRandomOrder()->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie->id/watch-later");

        $response->assertStatus(200);
    }

    public function test_create_watch_later_without_auth(): void
    {

        $movie = Movie::inRandomOrder()->first();

        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie->id/watch-later");

        $response->assertStatus(401);
    }

    public function test_create_watch_later_bad_movie(): void
    {
        $user = User::inRandomOrder()->first();
        $movieId = Movie::max('id');
        $movieId++;
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movieId/watch-later");

        $response->assertStatus(404);
    }
}
