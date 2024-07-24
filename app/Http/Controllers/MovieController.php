<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieCollection;
use App\Http\Resources\MovieResource;
use App\Models\Movie;

class MovieController extends Controller
{
    public function index()
    {
        $title = request('title');
        $genre = request('genre');
        
        $movies = Movie::when($title, function ($query) use($title){
                    $query->where('title', 'LIKE', '%'. $title .'%');
                })->when($genre, function ($query) use($genre){
                    $query->whereHas('genres', function ($q) use($genre){
                        $q->where('genres.id', $genre);
                });
                })->get();

        return response()->json([
            "movies" => new MovieCollection($movies)
        ]);
    }


    public function show(Movie $movie)
    {
        return response()->json([
            "movie" => new MovieResource($movie)
        ]);
    }



}
