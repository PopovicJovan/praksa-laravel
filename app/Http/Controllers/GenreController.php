<?php

namespace App\Http\Controllers;

use App\Http\Resources\Genre\GenreResource;
use App\Http\Resources\Movie\MovieCollection;
use App\Models\Genre;
use App\Models\Movie;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::all();
        return response()->json([
            'data' => GenreResource::collection($genres)
        ]);
    }

    public function getAllMovies(Genre $genre)
    {
        $movies = $genre->movies;
        return response()->json([
            "data" => new MovieCollection($movies)
        ]);
    }

    public function show(Genre $genre)
    {
        return response()->json([
            'data' => [
                new GenreResource($genre),
                new MovieCollection($genre->movies()->take(10)->get())
            ]
        ]);
    }

}
