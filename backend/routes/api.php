<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/news', [NewsController::class, 'getAll']);
Route::get('/news/{id}', [NewsController::class, 'get']);
Route::get('/news/{id}/comments', [CommentController::class, 'getByPost']);

Route::post('/news', [NewsController::class, 'create']);
Route::post('/comment', [CommentController::class, 'create']);

Route::put('/news/{id}/like', [NewsController::class, 'like']);
Route::put('/news/{id}/unlike', [NewsController::class, 'unlike']);

Route::delete('/news/{id}', [NewsController::class, 'delete']);
Route::delete('/comment/{id}', [CommentController::class, 'delete']);