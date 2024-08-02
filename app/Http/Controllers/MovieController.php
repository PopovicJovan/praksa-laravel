<?php

namespace App\Http\Controllers;

use App\Http\Resources\Movie\MovieResource;
use App\Http\Resources\Movie\MovieCollection;
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
            "data" => new MovieCollection($movies)
        ]);
    }


    public function show(Movie $movie)
    {
        return response()->json([
            "data" => new MovieResource($movie)
        ]);
    }

    public function getWatchLaterMovies(Request $request)
    {
        $user = $request->user();
        $movies = $user->watchLaterMovies;

        return response()->json([
            "movies" => new MovieCollection($movies)
        ]);
    }

    public function setWatchLaterMovies(Request $request, string $movie)
    {
        $user = $request->user();
        if (!Movie::find($movie))
            return response()->json([
                "message" => "Movie does not exist"
            ], 400);

        if (!$user->watchLaterMovies()->find($movie))
            $user->watchLaterMovies()->attach($movie);
        return response()->json([],200);
    }

    public function deleteWatchLaterMovies(Request $request, string $movie)
    {
        $user = $request->user();
        if (!$user->watchLaterMovies()->find($movie))
            $user->watchLaterMovies()->detach($movie);
        return response()->json([],200);
    }



}
