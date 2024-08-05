<?php

namespace App\Http\Controllers;

use App\Http\Resources\Comment\CommentCollection;
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

    public function store(Movie $movie, Request $request)
    {
        $user = $request->user();
        $comment = $request->input('comment');
        
        $request->validate(['comment' => 'required|string']);

        Comment::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'comment' => $comment
        ]);

        return response()->json([],200);

    }

    public function index(Movie $movie, Request $request)
    {
        $comments = $movie->comments->sortByDesc('updated_at');
        return response()->json([
            'data' => new CommentCollection($comments)
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        $request->validate(['comment' => 'required']);
        $comment->comment = $request->input('comment');
        $comment->save();
        return response()->json([], 200);
    }

    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->noContent();
    }

}
