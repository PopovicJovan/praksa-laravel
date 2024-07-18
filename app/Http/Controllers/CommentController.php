<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentCollection;
use App\Models\Comment;
use App\Models\Movie;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except('index');
        $this->authorizeResource(Comment::class, ['comment']);
    }

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

    public function index(string $movie, Request $request)
    {
        $movie = Movie::find($movie);
        if (!$movie) return response()->json([
            "message" => "Movie does not exist"
        ]);
        $comments = $movie->comments->sortByDesc('updated_at');
        return response()->json([
            'comments' => new CommentCollection($comments)
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $comment->comment = $request->input('comment');
        $comment->save();
        return response()->noContent();
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }

}
