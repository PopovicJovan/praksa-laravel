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
        $genres = request('movies') ? Genre::with('movies')->get() : Genre::all();
        return response()->json([
            'data' => GenreResource::collection($genres)
        ]);
    }

    public function getAllMovies(Genre $genre)
    {
        $paginate = request('paginate') ?? 10;
        $movies = $genre->movies()->paginate($paginate);
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
