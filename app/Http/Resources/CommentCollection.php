<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CommentCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return $this->collection->map(
            function ($comment){
                return[
                    'id' => $comment->id,
                    'user_id' => $comment->user_id,
                    'movie_id' => $comment->movie_id,
                    'comment' => $comment->comment
                ];
            }
        )->all();
    }
}
