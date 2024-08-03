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
        $parent_id = $request->input('parent_id');
        if($parent_id)
            if(Comment::find($parent_id)->parent_id != null) return response()->json();
        $request->validate(['comment' => 'required|string']);

        Comment::create([
            'user_id' => $user->id,
            'movie_id' => $movie->id,
            'comment' => $comment,
            'parent_id' => $parent_id
        ]);

        return response()->json([],200);

    }

    public function index(Movie $movie, Request $request)
    {
        $comments = Comment::where('movie_id', $movie->id)
            ->whereNull('parent_id')
            ->with('replies')
            ->get();
        return response()->json([
            'data' => new CommentCollection($comments)
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        if (!$request->input('comment')) return response()->json([], 200);
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
