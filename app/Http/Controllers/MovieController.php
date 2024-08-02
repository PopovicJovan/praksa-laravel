<?php

namespace App\Http\Controllers;

use App\Http\Resources\Movie\MovieResource;
use App\Http\Resources\Movie\MovieCollection;
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
            "data" => new MovieCollection($movies)
        ]);
    }

    public function setWatchLaterMovies(Request $request, Movie $movie)
    {
        $user = $request->user();
        if (!$user->watchLaterMovies()->find($movie->id))
            $user->watchLaterMovies()->attach($movie->id);
        return response()->json([],200);
    }

    public function deleteWatchLaterMovies(Request $request, Movie $movie)
    {
        $user = $request->user();
        if ($user->watchLaterMovies()->find($movie->id))
            $user->watchLaterMovies()->detach($movie->id);
        return response()->json([],200);
    }



}
