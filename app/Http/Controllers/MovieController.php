<?php

namespace App\Http\Controllers;

use App\Http\Resources\MovieResource;
use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function index()
    {
        $title = request('title');

        $movies = Movie::when($title, function ($query) use($title){
            $query->where('title', 'LIKE', '%'. $title .'%');
        })->get();

        return response()->json([
            "movies" => MovieResource::collection($movies)
        ]);
    }

    public function store(Request $request)
    {
        //
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

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
