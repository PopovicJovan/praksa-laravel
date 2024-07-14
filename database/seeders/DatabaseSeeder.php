<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;


class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $header = [
            'Authorization' => 'Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiJiNWVhZmRmNGUyOGYzY2Y2NGZkYWUxOGRkZDNmMmFhZSIsIm5iZiI6MTcyMDc5MjQ5Ny44OTUwMTIsInN1YiI6IjY2OGZhMzU3ZDQyOWU4OTcyMWQ1MmI4NCIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.ETEr3-rrUp58ctEqXf_eyRv4PmaQJLCwYQOJhyPl2kQ',
            'accept' => 'application/json',
        ];

        $genres = Http::withHeaders($header)->get('https://api.themoviedb.org/3/genre/movie/list?language=en')->body();
        $genres = json_decode($genres, true);
        $genres = $genres['genres'];
        foreach ($genres as $genre) {
            $g = new Genre([
                'id' => $genre['id'],
                'title' => $genre['name']
            ]);
            $g->save();
        }

        for ($i = 1; $i < 100 ; $i++) {
            try {
                $movies = Http::withHeaders($header)->get('https://api.themoviedb.org/3/discover/movie?page=' . $i)->body();
                $movies = json_decode($movies, true);
                $movies = $movies['results'];
                foreach ($movies as $movie) {
                    try {
                        $m = new Movie([
                            'id' => $movie['id'],
                            'adult' => false,
                            'title' => $movie['title'],
                            'overview' => $movie['overview'],
                            'release_date' => $movie['release_date'],
                            'poster_path' => $movie['poster_path'],
                        ]);
                        if ($movie['adult']) $m->adult = true;
                        $m->save();
                        $genres = $movie['genre_ids'];
                        $m->genres()->sync($genres);
                    } catch (QueryException $e) {}
                }
            } catch (\ErrorException $e){}
        }
    }

}
