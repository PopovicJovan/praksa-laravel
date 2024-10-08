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
        'trailer_link',
        'parent_id'
    ];



    public function genres(): BelongsToMany
    {
        return $this->belongsToMany(Genre::class);
    }

    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function usersWatchingLater(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'watch_later');
    }

    public function getAllSearchedMovies(?string $title, ?array $genres, $paginate)
    {
        return $this->when($title, function ($query) use ($title) {
            $query->where('title', 'LIKE', '%' . $title . '%');
        })
            ->when($genres, function ($query) use ($genres) {
                foreach ($genres as $genre) {
                    $query->whereHas('genres', function ($q) use ($genre) {
                        $q->where('genres.id', $genre);
                    });
                }
            })
            ->paginate($paginate);
    }

}
