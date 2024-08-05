<?php

namespace Tests\Feature\Comment;

use App\Models\Movie;
use Tests\TestCase;

class GetCommentTest extends TestCase
{
    public function test_get_all_comments_from_movie(): void
    {
        $movie = Movie::inRandomOrder()->first();
        $response = $this->get("/api/movie/$movie->id/comment");
        if(!empty($response->json('data'))){
            $response->assertJsonStructure([
                "data" => [
                    "*" => [
                        'id', 'user_id',
                        'user_name', 'movie_id',
                        'comment', 'created_at',
                        'parent_id', 'replies'
                    ]
                ]
            ]);
        }else{
            $response->assertJsonStructure([
                "data" => []
            ]);
        }

        $response->assertStatus(200);
    }

    public function test_get_all_comments_from_movie_that_does_not_exist(): void
    {
        $movieId = Movie::max('id');
        $movieId++;
        $response = $this->get("/api/movie/$movieId/comment");
        $response->assertStatus(404);
    }
}
