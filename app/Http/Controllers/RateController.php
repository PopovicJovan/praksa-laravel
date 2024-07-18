<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Rate;
use App\Models\User;
use Illuminate\Http\Request;

class RateController extends Controller
{
    public function store(string $movieId, Request $request)
    {
        $user = $request->user();
        $rate = $request->input('rate');

        $movie = Movie::find($movieId);

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
        Rate::updateOrCreate(
            ['user_id' => $user->id, 'movie_id' => $movieId],
            [
                'user_id' => $user->id,
                'movie_id' => $movieId,
                'rate' => $rate
        ]);

        $movie->vote_count += 1;
        $movie->vote_average = $movie->rates()->avg('rate');
        $movie->save();
    }
}
