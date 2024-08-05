<?php

namespace Tests\Feature\Auth;


use App\Models\User;
use Tests\TestCase;

class RegistrationTest extends TestCase
{


    public function test_user_can_register(): void
    {

        $email = fake()->unique()->safeEmail();
        $response = $this->post('/api/register', [
            'name' => fake()->name(),
            'email' => $email,
            'password' => 'password'
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => $email,
        ]);

    }

    public function test_user_exists(): void
    {
        $email = User::inRandomOrder()->first()->email;

        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/register', [
            'name' => fake()->name(),
            'email' => $email,
            'password' => 'password'
        ]);

        $response->assertStatus(422);
    }

    public function test_bad_email()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/register', [
            'name' => fake()->name(),
            'email' => 'abc',
            'password' => 'password'
        ]);

        $response->assertStatus(422);

    }

    public function test_empty_name_or_password()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post('/api/register', [
            'name' => '',
            'email' => 'abc@gmail.com',
            'password' => ''
        ]);

        $response->assertStatus(422);
    }
}
