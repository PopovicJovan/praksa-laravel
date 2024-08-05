<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

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
}
