<?php

namespace App\Http\Resources\Genre;

use App\Http\Resources\Movie\MovieCollection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GenreResource extends JsonResource
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
            'title' => $this->title,
            'movies' => new MovieCollection($this->whenLoaded('movies', function (){
                return $this->movies->take(10);
            }))
        ];
    }
}
