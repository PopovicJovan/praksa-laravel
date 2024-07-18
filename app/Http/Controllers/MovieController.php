<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $title = request('title');
        $genre = request('genre');
        
        $movies = Movie::when($title, function ($query) use($title){
            $query->where('title', 'LIKE', '%'. $title .'%');
        })->get();

        if ($genre) {
            $movies = $movies->filter(function ($movie) use ($genre) {
                return $movie->genres->contains('title', $genre);
            });
        }


        return response()->json([
            "movies" => new MovieCollection($movies->sortByDesc('popularity'))
        ]);
    }


    public function show(string $id)
    {
        $movie = Movie::find($id);

        if (!$movie) return response()->json([
            "message" => "Movie does not exist"
        ], 404);

        return response()->json([
            "movie" => new MovieResource($movie)
        ]);
    }


}
