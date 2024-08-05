<?php

namespace App\Http\Controllers;

use App\Http\Resources\Rate\RateResource;
use App\Models\Movie;
use App\Models\Rate;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(Movie $movie, Request $request)
    {
        $user = $request->user();
        $rate = (int)$request->input('rate');

        $request->validate(['rate' => 'required|integer|between:1,5']);

        if (!Rate::where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first()){
            $movie->vote_count += 1;
        }
        Rate::updateOrCreate(
            ['user_id' => $user->id, 'movie_id' => $movie->id],
            [
                'user_id' => $user->id,
                'movie_id' => $movie->id,
                'rate' => $rate
        ]);

        $movie->vote_average = $movie->rates()->avg('rate');
        $movie->save();
        return response()->json([], 200);
    }

    public function show(Movie $movie, Request $request)
    {
        $rate = Rate::where('movie_id', $movie->id)
                ->where('user_id', $request->user()->id)
                ->firstOrFail();
        return response()->json([
            "data" => new RateResource($rate)
        ], 200);
    }
}
