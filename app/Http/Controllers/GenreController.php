<?php

namespace App\Http\Controllers;

use App\Http\Resources\Genre\GenreResource;
use App\Http\Resources\Movie\MovieCollection;
use App\Models\Genre;

class GenreController extends Controller
{
    public function index()
    {
        $genres = request('movies') ? Genre::with('movies')->get() : Genre::all();
        return response()->json([
            'data' => GenreResource::collection($genres)
        ]);
    }

    public function getAllMovies(Genre $genre)
    {
        $movies = $genre->movies()->paginate(10);
        return response()->json([
            "data" => [
                new GenreResource($genre),
                new MovieCollection($movies)
            ],
            "last_page" => $movies->lastPage()
        ]);
    }

    public function show(Genre $genre)
    {
        $genre = request('movies') ? Genre::with('movies')->find($genre->id) : $genre;
        return response()->json([
            'data' => [
                new GenreResource($genre)
            ]
        ]);
    }

}
