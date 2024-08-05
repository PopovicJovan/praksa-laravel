<?php

namespace Tests\Feature\Movie;

use App\Models\Genre;
use App\Models\Movie;
use Tests\TestCase;

class GetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all(): void
    {
        $response = $this->get('/api/movie');

        $response->assertStatus(200);
        $response->assertJsonStructure([
           "data" =>
               ["*" => [ 'id',
                        'adult',
                        'title',
                        'poster_path',
                        'genres'
                        ]
               ]
        ]);
    }

    public function test_get_single_movie_exists()
    {
        $movie = Movie::inRandomOrder()->first()->id;
        $response = $this->get("/api/movie/$movie");
        $response->assertStatus(200);
        $response->assertJsonStructure([
               "data" => [
                   'id', 'adult',
                   'title', 'overview',
                   'popularity', 'vote_average',
                   'vote_count', 'release_date',
                   'poster_path', 'genres',
                   'comment_count'
               ]
        ]);
    }

    public function test_get_single_movie_does_not_exist()
    {
        $response = $this->get("/api/movie/0");
        $response->assertStatus(404);
    }

    public function test_search_movie_by_genre_exists()
    {
        $genre = Genre::inRandomOrder()->first()->id;
        $response = $this->get("api/movie?genre=$genre");
        $response->assertStatus(200);
        foreach ($response->json('data') as $movie){
            $genres = $movie['genres'];
            $contain = false;
            foreach ($genres as $g){
                if ($g['id'] == $genre){
                    $contain = true;
                    break;
                }
            }
            $this->assertTrue($contain);
        }
    }

    public function test_search_movie_by_genre_that_does_not_exist()
    {
        $response = $this->get("/api/movie?genre=1");
        $empty =  empty($response->json('data'));
        $this->assertTrue($empty);
    }
}
