<?php

namespace Tests\Feature\Comment;

use App\Models\Comment;
use App\Models\User;
use Tests\TestCase;

class UpdateCommentTest extends TestCase
{

    public function test_update_comment(): void
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;
        $email = User::find($comment->user_id)->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->put("/api/comment/$commentId?comment=promjena");
        $response->assertStatus(200);
        $this->assertTrue(Comment::find($commentId)->comment == 'promjena');
    }

    public function test_update_comment_without_auth(): void
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;

        $response = $this->withHeaders([
            "Authorization" => "Bearer ",
            "Accept" => "application/json"
        ])->put("/api/comment/$commentId?comment=promjena");
        $response->assertStatus(401);
    }

    public function test_update_comment_without_comment(): void
    {
        $comment = Comment::inRandomOrder()->first();
        $commentId = $comment->id;
        $email = User::find($comment->user_id)->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->put("/api/comment/$commentId?comment=");
        $response->assertStatus(422);
    }

    public function test_update_comment_bad_comment(): void
    {

        $commentId = Comment::max('id');
        $commentId++;
        $email = User::inRandomOrder()->first()->email;
        $token = $this->login($email);

        $response = $this->withHeaders([
            "Authorization" => "Bearer $token",
            "Accept" => "application/json"
        ])->put("/api/comment/$commentId?comment=promjena");
        $response->assertStatus(404);
    }

    public function test_update_comment_forbidden()
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
        ])->put("/api/comment/$commentId?comment=promjena");
        $response->assertStatus(403);
    }


}
