<?php

namespace Tests\Feature\WatchLater;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class GetWatchLaterTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_watch_later_movies(): void
    {
        $watch_later = DB::table('watch_later')->inRandomOrder()->first();
        $userId = $watch_later->user_id;
        $token = $this->login(User::find($userId)->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->get('api/watch-later');

        if(!empty($response->json())){
            $response->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id', 'title',
                        'adult', 'poster_path',
                        'genres' => [
                            "*" => ['id', 'title']
                        ]
                    ]
                ]
            ]);
        }

        $response->assertStatus(200);
    }

    public function test_get_all_watch_later_movies_without_auth(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->get('api/watch-later');

        $response->assertStatus(401);
    }
}
