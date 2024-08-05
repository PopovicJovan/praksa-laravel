<?php

namespace Tests\Feature\Rate;

use App\Models\Movie;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetRateTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function login($email)
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/login', [
            'email' => $email,
            'password' => 'password'
        ]);
        return $response->json('token');
    }

    public function test_get_rate_that_exists(): void
    {
        $rate = Rate::inRandomOrder()->first();
        $token = $this->login(User::find($rate->user_id)->email);
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => "application/json"
        ])->get("api/movie/$rate->movie_id/rate");

        $this->assertFalse(empty($response->json('data')['rate']));
        $response->assertStatus(200);
    }

    public function test_get_rate_that_does_not_exist()
    {
        $users = Rate::pluck('user_id')->toArray();
        $user = User::whereNotIn('id', $users)->get()->first();
        $movie = Movie::inRandomOrder()->first();

        $token = $this->login($user->email);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => "application/json"
        ])->get("api/movie/$movie->id/rate");

        $this->assertTrue(empty($response->json('data')['rate']));
        $response->assertStatus(404);
    }

    public function test_get_rate_where_movie_does_not_exist()
    {
        $user = User::inRandomOrder()->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => "application/json"
        ])->get("api/movie/1/rate");

        $response->assertStatus(404);
    }

    public function test_get_rate_without_auth()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer ",
            'Accept' => "application/json"
        ])->get("api/movie/1/rate");

        $response->assertStatus(401);
    }
}
