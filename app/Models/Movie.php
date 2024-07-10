<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Movie extends Model
{
    use HasFactory;
    protected $fillable = [
        'id',
        'adult',
        'genre',
        'title',
        'overview',
        'popularity',
        'vote_avarage',
        'vote_count',
    ];

    public function genre(): HasOne
    {
        return $this->hasOne(Genre::class);
    }
}
