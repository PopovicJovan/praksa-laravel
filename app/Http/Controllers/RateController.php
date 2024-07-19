<?php

namespace App\Http\Controllers;

use App\Http\Resources\RateResource;
use App\Models\Movie;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(string $movie, Request $request)
    {
        $user = $request->user();
        $rate = $request->input('rate');

        $movie = Movie::find($movie);

        if (!$movie){
            return response()->json([
                "message" => "Movie does not exist"
            ], 400);
        }
        if ($rate > 5 or $rate < 1) {
            return response()->json([
                "message" => "Value of rate has to be between 1 and 5"
            ], 400);
        }
        if (!Rate::where('user_id', $user->id)->where('movie_id', $movie->id)->first()){
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
        return response()->noContent();
    }

    public function show(string $movie, Request $request)
    {
        $rate = Rate::where('movie_id', $movie)->where('user_id', $request->user()->id)->first();
        return response()->json([
            "rate" => new RateResource($rate)
        ]);
    }
}
