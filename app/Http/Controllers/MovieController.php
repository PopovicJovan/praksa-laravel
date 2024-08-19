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

        $paginate = request('paginate') ?? 10;
        $movies = (new Movie())->getAllSearchedMovies($title, $genre, $paginate);
        return response()->json([
            "data" => new MovieCollection($movies),
            "last_page" => $movies->lastPage()
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
        $paginate = request('paginate') ?? 10;
        $movies = $user->watchLaterMovies()->paginate($paginate);

        return response()->json([
            "data" => new MovieCollection($movies),
            "last_page" => $movies->lastPage()
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
