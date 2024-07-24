<?php

namespace App\Http\Resources;

use App\Models\Movie;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovieResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'adult' => $this->adult,
            'title' => $this->title,
            'overview' => $this->overview,
            'popularity' => $this->popularity,
            'vote_average' => $this->vote_average,
            'vote_count' => $this->vote_count,
            'release_date' => $this->release_date,
            'poster_path' => $this->poster_path,
            'genres' => GenreResource::collection($this->genres),
            'comment_count' => $this->comments->count()
        ];
    }
}
