<?php

namespace App\Http\Controllers;

use App\Http\Resources\Cast\CastCollection;
use App\Http\Resources\Cast\CastResource;
use App\Models\Cast;
use App\Models\Movie;
use Illuminate\Http\Request;

class CastController extends Controller
{
    public function index(Movie $movie)
    {
        $cast = new CastCollection($movie->cast);
        return response()->json([
            "data" => $cast
        ]);
    }

    public function show(Cast $cast)
    {
        $cast = new CastResource($cast);
        return response()->json([
            "data" => $cast
        ]);
    }
}
