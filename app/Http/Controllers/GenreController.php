<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieCollection;
use App\Models\Genre;
use App\Models\Movie;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::all();
        return response()->json([
            'genres' => GenreResource::collection($genres)
        ]);
    }

    public function getAllMovies(Genre $genre)
    {
        $movies = Movie::whereHas('genres', function ($query) use ($genre){
            $query->where('genres.id', $genre->id);
        })->get();

        return response()->json([
            "movies" => new MovieCollection($movies)
        ]);
    }

    public function show(Genre $genre)
    {
        return response()->json([
            'genre' => new GenreResource($genre)
        ]);
    }

}
