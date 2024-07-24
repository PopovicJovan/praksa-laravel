<?php

namespace App\Http\Resources;

use App\Models\User;
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
                    'user_name' => User::find($comment->user_id)->name,
                    'movie_id' => $comment->movie_id,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at
                ];
            }
        )->all();
    }
}
