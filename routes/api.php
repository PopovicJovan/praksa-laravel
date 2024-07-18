<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('/movie', MovieController::class)
    ->only(['index', 'show']);

Route::apiResource('/genre', GenreController::class)->only(['index', 'show']);
Route::get('/genre/{genre}/movie', [GenreController::class, 'getAllMovies']);

Route::post('/movie/{movie}/rate', [RateController::class, 'store'])->middleware('auth:sanctum');
Route::get('/movie/{movie}/rate', [RateController::class, 'show'])->middleware('auth:sanctum');
Route::post('/movie/{movie}/comment', [CommentController::class, 'store'])->middleware('auth:sanctum');
Route::get('/movie/{movie}/comment', [CommentController::class, 'index']);
Route::put('/comment/{comment}', [CommentController::class, 'update'])->middleware('auth:sanctum');
Route::delete('/comment/{comment}', [CommentController::class, 'destroy'])->middleware('auth:sanctum');

Route::get('/movie/picture/{width}/{path}', [MovieController::class, 'getPicture']);