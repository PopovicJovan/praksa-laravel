<?php

namespace Tests\Feature\Genre;

use App\Models\Genre;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GetTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_get_all_genres(): void
    {
        $response = $this->get('/api/genre');
        $response->assertJsonStructure([
            "data" => [
                "*" => [
                    "id", "title"
                ]
            ]
        ]);
        $response->assertStatus(200);
    }

    public function test_get_single_genre_that_exists()
    {
        $genre = Genre::inRandomOrder()->first()->id;
        $response = $this->getJson("/api/genre/$genre");
        $response->assertJsonStructure([
            "data" => [
                ["id", "title"],
                [
                    "*" => [
                        "id", "title",
                        "adult", "poster_path",
                        "genres" => [
                            "*" => ["id", "title"]
                        ]
                    ]
                ]
            ]
        ]);

        $response->assertStatus(200);
    }

    public function test_get_single_genre_that_does_not_exist()
    {
        $response = $this->getJson("/api/genre/1");
        $response->assertStatus(404);
    }

    public function test_get_all_movies()
    {
        $genre = Genre::inRandomOrder()->first()->id;
        $response = $this->get("api/genre/$genre/movie");
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
}
