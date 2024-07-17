<?php

namespace App\Console\Commands;

use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

class PopulateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populating the database';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $header = [
            'Authorization' => config('env.api_auth_key'),
            'accept' => 'application/json',
        ];
        $api_url = config('env.api_url');


        $genres = Http::withHeaders($header)->get("$api_url/genre/movie/list?language=en")->body();
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
                $movies = Http::withHeaders($header)->get("$api_url/discover/movie?page=$i")->body();
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
                            'popularity' => $movie['popularity'],
                            'vote_average' => $movie['vote_average'],
                            'vote_count' => $movie['vote_count']
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
