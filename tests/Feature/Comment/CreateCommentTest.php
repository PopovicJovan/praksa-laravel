<?php

namespace Tests\Feature\Comment;

use App\Models\Movie;
use App\Models\User;
use Tests\TestCase;

class CreateCommentTest extends TestCase
{

    public function test_create_comment_without_auth(): void
    {
        $movie = Movie::inRandomOrder()->first();
        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie->id/comment?comment=komentarkomentar");
        $response->assertStatus(401);
    }

    public function test_create_comment()
    {
        $movie = Movie::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie->id/comment?comment=komentarkomentar");
        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'comment' => 'komentarkomentar'
        ]);
    }

    public function test_create_comment_where_movie_does_not_exist()
    {
        $user = User::inRandomOrder()->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/1/comment?comment=komentarkomentar");
        $response->assertStatus(404);
    }

    public function test_create_comment_with_empty_comment()
    {
        $movie = Movie::inRandomOrder()->first();
        $user = User::inRandomOrder()->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->post("/api/movie/$movie->id/comment?comment=");
        $response->assertStatus(422);
    }
}
