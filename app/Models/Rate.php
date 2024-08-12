<?php

namespace App\Models;

use http\Env\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = ['id', 'user_id', 'movie_id', 'rate'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function getUserRate(User $user, Movie $movie)
    {
        return $this->where('user_id', $user->id)
            ->where('movie_id', $movie->id)
            ->first();
    }

    public function getUserRateOrFail(Movie $movie)
    {
        return $this->where('movie_id', $movie->id)
            ->where('user_id', request()->user()->id)
            ->firstOrFail();
    }
}
