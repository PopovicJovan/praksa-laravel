<?php

namespace App\Console\Commands;

use App\Models\Cast;
use App\Models\Movie;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class PopulateCast extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate:cast';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populating the database with cast and movie-cast relations';

    /**
     * Execute the console command.
     */
    public function handle()
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


        foreach (Movie::all() as $movie) {
            $id = $movie->id;
            $cast = $fetch("movie/$id/credits?language=en-US", 'cast');
            $castIds = [];
            foreach ($cast as $actor){
                Cast::updateOrCreate(
                    ['id' => $actor['id']],
                    [
                        'id' => $actor['id'],
                        'name'=>  $actor['original_name'],
                        'image_path' => $actor['profile_path'] ?? "no-image",
                        'role' => $actor['known_for_department'],
                        'about' => $actor['biography']
                    ]
                );
                $castIds[] = $actor['id'];
            }
            (Movie::find($id))->cast()->sync($castIds);
        }
    }
}
