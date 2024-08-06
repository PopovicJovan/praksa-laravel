<?php

namespace App\Console\Commands;


use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Http;

class PopulateGenreMovie extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:genre-movie';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populating the database with genres and movies';

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

        $fetch = function ($url, $name) use($header, $api_url)
        {
            $var = Http::withHeaders($header)->get("$api_url/$url")->body();
            $var = json_decode($var, true);
            return $var[$name];
        };


        $genres = $fetch('genre/movie/list?language=en', 'genres');
        foreach ($genres as $genre) {
            $g = Genre::updateOrCreate(
                ['id' => $genre['id']],
                ['title' => $genre['name']]
            );
        }

        for ($i = 1; $i < 15 ; $i++) {
            try {
                $movies = $fetch("discover/movie?page=$i", 'results');
                foreach ($movies as $movie) {
                    try {
                        $m = Movie::updateOrCreate(
                            ['id' => $movie['id']],
                            [   'title' => $movie['title'],
                                'overview' => $movie['overview'],
                                'release_date' => $movie['release_date'],
                                'poster_path' => $movie['poster_path'],
                                'popularity' => $movie['popularity']
                            ]
                        );
                        $movie['adult'] ? $m->adult = true : $m->adult = false;
                        $m->save();
                        $m->genres()->sync($movie['genre_ids']);
                        $results = $fetch("movie/$m->id/videos?language=en-US", 'results');
                        foreach ($results as $result){
                            if($result['type'] == "Trailer"){
                                $m->trailer_link = $result['key'];
                                $m->save();
                                break;
                            }
                        }

                    } catch (QueryException $e) {}
                }
            } catch (\ErrorException $e){}
        }
    }
}
