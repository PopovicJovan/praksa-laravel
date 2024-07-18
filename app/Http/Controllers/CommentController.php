<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(string $movie, Request $request)
    {
        $user = $request->user();
        $comment = $request->input('comment');
        $movie = Movie::find($movie);

        if (!$movie){
            return response()->json([
                "message" => "Movie does not exist"
            ], 400);
        }

        Comment::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'comment' => $comment
        ]);

        return response()->noContent();

    }

    public function getAllComment(string $movie, Request $request)
    {
        $comments = Movie::find($movie)->comments->sortByDesc('updated_at');
        return response()->json([
            'comments' => new CommentCollection($comments)
        ]);
    }

    public function update(string $comment, Request $request)
    {
        $comment = Comment::find($comment);
        $comment->update($request->only('comment'));
        $comment->save();
        return response()->noContent();
    }

    public function destroy(string $comment)
    {
        $comment = Comment::find($comment);
        $comment->delete();
        return response()->noContent();
    }

}
