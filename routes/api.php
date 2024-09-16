<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReplyController;

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
Route::get('/comments', [CommentController::class, 'index']); // Показ всех комментариев и ответов
Route::post('/comments', [CommentController::class, 'create']); // Создание нового комментария или ответа
//Route::get('/comments/{commentId}', [CommentController::class, 'show']); // Показ конкретного комментария
//Route::put('/comments/{commentId}', [CommentController::class, 'update']); // Обновление комментария
//Route::delete('/comments/{commentId}', [CommentController::class, 'destroy']); // Удаление комментария

Route::get('/comments/{comment}/replies', [CommentController::class, 'replies']);
Route::post('/comments/{comment}/replies', [CommentController::class, 'createReply']);

Route::get('/comments/{comment}/replies/{reply}/replies', [CommentController::class, 'getRepliesToReply']);
Route::post('/comments/{comment}/replies/{reply}/replies', [CommentController::class, 'createReplyToReply']);
