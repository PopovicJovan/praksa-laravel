<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;
use App\Http\Controllers\RateController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CastController;

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

Route::post('/register', [RegisteredUserController::class, 'store']);
Route::post('/login', [AuthenticatedSessionController::class, 'store']);
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->middleware('auth:sanctum');

Route::apiResource('/movie', MovieController::class)
    ->only(['index', 'show']);

Route::apiResource('/genre', GenreController::class)
    ->only(['index', 'show']);
Route::get('/genre/{genre}/movie', [GenreController::class, 'getAllMovies']);

Route::get('/movie/{movie}/comment', [CommentController::class, 'index']);
Route::get('/movie/{movie}/cast', [CastController::class, 'index']);
Route::get('/cast/{cast}', [CastController::class, 'show']);


Route::middleware('auth:sanctum')->group(function (){
    Route::post('/movie/{movie}/rate', [RateController::class, 'store']);
    Route::get('/movie/{movie}/rate', [RateController::class, 'show']);
    Route::get('/watch-later', [MovieController::class, 'getWatchLaterMovies']);
    Route::post('/movie/{movie}/watch-later', [MovieController::class, 'setWatchLaterMovies']);
    Route::delete('/movie/{movie}/watch-later', [MovieController::class, 'deleteWatchLaterMovies']);
    Route::post('/movie/{movie}/comment', [CommentController::class, 'store']);
    Route::put('/comment/{comment}', [CommentController::class, 'update']);
    Route::delete('/comment/{comment}', [CommentController::class, 'destroy']);
});