<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

// Основные маршруты для комментариев
Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'create']);

Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);
Route::post('/comments/{comment}/replies', [CommentController::class, 'createReply']);

//Route::post('/upload-avatar', [CommentController::class, 'uploadAvatar']);

Route::get('/comments/{comment}/replies/{reply}/replies', [CommentController::class, 'getRepliesToReply']);
Route::post('/comments/{comment}/replies/{reply}/replies', [CommentController::class, 'createReplyToReply']);
