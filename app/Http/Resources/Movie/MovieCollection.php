<?php

namespace App\Http\Resources\Movie;

use App\Http\Resources\Cast\CastCollection;
use App\Http\Resources\Genre\GenreResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MovieCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(
            function ($movie){
                return [
                    'id' => $movie->id,
                    'title' => $movie->title,
                    'adult' => $movie->adult,
                    'poster_path' => $movie->poster_path,
                    'genres' => GenreResource::collection($movie->genres)
                ];
            }
        )->all();
    }
}
