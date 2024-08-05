<?php

namespace Tests\Feature\WatchLater;

use App\Models\Movie;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class DeleteWatchLaterTest extends TestCase
{

    public function test_delete_watch_later(): void
    {
        $watch_later = DB::table('watch_later')->inRandomOrder()->first();
        $userId = $watch_later->user_id;
        $movieId = $watch_later->movie_id;
        $token = $this->login(User::find($userId)->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->delete("api/movie/$movieId/watch-later");

        $this->assertTrue(
            is_null(
                DB::table('watch_later')
                    ->where('user_id', $userId)
                    ->where('movie_id', $movieId)
                    ->first()
            )
        );

        $response->assertStatus(200);
    }

    public function test_delete_watch_later_without_auth(): void
    {
        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->delete("api/movie/13/watch-later");

        $response->assertStatus(401);
    }

    public function test_delete_watch_later_bad_movie(): void
    {

        $userId = User::inRandomOrder()->first()->id;
        $movieId = Movie::max('id');$movieId++;
        $token = $this->login(User::find($userId)->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->delete("api/movie/$movieId/watch-later");

        $response->assertStatus(404);
    }
}
