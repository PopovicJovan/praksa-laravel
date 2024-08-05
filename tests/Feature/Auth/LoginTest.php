<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_bad_email(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/login', [
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function test_bad_password(): void
    {
        $email = User::inRandomOrder()->first()->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/login', [
            'email' => $email,
            'password' => 'password1234'
        ]);

        $response->assertStatus(422);
    }

    public function test_ok_login_and_logout(): void
    {
        $email = User::inRandomOrder()->first()->email;
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/login', [
            'email' => $email,
            'password' => 'password'
        ]);

        $response->assertStatus(200);
        $response->assertJson(["token" => true]);

        $token = $response->json('token');

        $response = $this->withHeaders([
            'Authorization' => "Bearer $token",
            'Accept' => 'application/json'
        ])->post('/api/logout');

        $response->assertStatus(204);
    }

    public function test_bad_token_login()
    {
        $response = $this->withHeaders([
            'Authorization' => "Bearer ",
            'Accept' => 'application/json'
        ])->post('/api/logout');

        $response->assertStatus(401);
    }

}
