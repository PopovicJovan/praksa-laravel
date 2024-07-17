<?php

namespace App\Http\Controllers;

use App\Http\Resources\GenreResource;
use App\Http\Resources\MovieCollection;
use App\Models\Genre;
use App\Models\Movie;
use Illuminate\Http\Request;

class GenreController extends Controller
{
    public function index()
    {
        $genres = Genre::all();
        return response()->json([
            'genres' => GenreResource::collection($genres)
        ]);
    }

    public function getAllMovies(string $genre)
    {
        $movies = Movie::all();
        $movies = $movies->filter(function ($movie) use ($genre) {
            return $movie->genres->contains('id', $genre);
        });

        return response()->json([
            "movies" => new MovieCollection($movies)
        ]);
    }

    public function show(string $id)
    {
        $genre = Genre::find($id);
        return response()->json([
            'genre' => new GenreResource($genre)
        ]);
    }

}
