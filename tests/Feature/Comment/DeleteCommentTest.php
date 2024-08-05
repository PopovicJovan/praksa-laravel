<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class DeleteCommentTest extends TestCase
{
    public function test_delete_comment(): void
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;
        $email = User::find($comment->user_id)->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->delete("/api/comment/$commentId");
        $response->assertStatus(204);
    }

    public function test_delete_comment_without_auth(): void
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;

        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->delete("/api/comment/$commentId");
        $response->assertStatus(401);
    }


    public function test_delete_comment_bad_comment(): void
    {
        $commentId = Comment::max('id');
        $commentId++;
        $email = User::inRandomOrder()->first()->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->delete("/api/comment/$commentId");
        $response->assertStatus(404);
    }

    public function test_delete_comment_forbidden()
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;
        $user =  User::where('id', '!=', $comment->user_id)
            ->inRandomOrder()
            ->first();
        $token = $this->login($user->email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->delete("/api/comment/$commentId");
        $response->assertStatus(403);
    }
}
