<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'adult',
        'title',
        'overview',
        'popularity',
        'vote_average',
        'vote_count',
        'release_date',
        'poster_path',
    ];

    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }
}
