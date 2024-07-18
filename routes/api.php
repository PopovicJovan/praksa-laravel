<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\GenreController;

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
//    ->middleware('auth:sanctum');

Route::apiResource('/genre', GenreController::class);
Route::get('/genre/{genre}/movies', [GenreController::class, 'getAllMovies']);

Route::post('/movie/{movieId}/rate', [\App\Http\Controllers\RateController::class, 'store'])->middleware('auth:sanctum');