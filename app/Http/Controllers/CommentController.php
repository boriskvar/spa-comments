<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Получение списка всех комментариев.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $comments = Comment::whereNull('parent_id')->with('replies')->get();
        return response()->json($comments);
    }

    /**
     * Сохранение нового комментария.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:comments,id',
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'text' => 'required|string',
            'home_page' => 'nullable|url',
            'captcha' => 'required|string',
            'rating' => 'nullable|integer',
            'quote' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,gif,txt',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('files', 'public');
        }

        $comment = new Comment();
        $comment->parent_id = $validated['parent_id'] ?? null;
        $comment->user_name = $validated['user_name'];
        $comment->email = $validated['email'];
        $comment->text = $validated['text'];
        $comment->home_page = $validated['home_page'] ?? null;
        $comment->captcha = $validated['captcha'];
        $comment->rating = $validated['rating'] ?? null;
        $comment->quote = $validated['quote'] ?? null;
        $comment->avatar = $avatarPath ? str_replace('public/', '', $avatarPath) : null; // Убираем 'public/' из пути
        $comment->file_path = $filePath ? str_replace('public/', '', $filePath) : null; // Убираем 'public/' из пути
        $comment->save();

        // Формируем URL для аватара и файла
        $comment->avatarUrl = $comment->avatar ? "/storage/{$comment->avatar}" : null;
        $comment->fileUrl = $comment->file_path ? "/storage/{$comment->file_path}" : null;


        return response()->json($comment, 201);
    }

    /**
     * Обновление существующего комментария.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */

    /*public function update(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:comments,id',
            'user_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'text' => 'required|string',
            'home_page' => 'nullable|url',
            'captcha' => 'required|string',
            'rating' => 'nullable|integer',
            'quote' => 'nullable|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
            'file_path' => 'nullable|file|mimes:jpg,jpeg,png,gif,txt',
        ]);

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('public/images/avatars');
            $comment->avatar = $avatarPath;
        }

        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('public/files');
            $comment->file_path = $filePath;
        }

        $comment->update($validated);

        return response()->json($comment);
    }*/

    /**
     * Удаление существующего комментария.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    /*public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
    }*/

    public function replies(Comment $comment)
    {
        $replies = $comment->replies;
        return response()->json($replies);
    }

    public function createReply(Request $request, Comment $comment)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'text' => 'required|string',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif',
        ]);

        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            // Сохраняем файл в папку public/avatars
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $reply = $comment->replies()->create([
            'user_name' => $validated['user_name'],
            'avatar' => $avatarPath ? str_replace('public/', '', $avatarPath) : null, // В avatar используется str_replace('public/', '', $avatarPath) для удаления 'public/' из пути.
            'text' => $validated['text'],
            'parent_id' => $comment->id,
            // Другие необходимые поля, такие как user_id, можно добавить по мере необходимости
        ]);

        // Формируем URL для аватара
        $reply->avatarUrl = $reply->avatar ? "/storage/{$reply->avatar}" : null;


        // Загрузите вложенные ответы
        $reply->load('replies');

        return response()->json($reply, 201); // Возвращаем только что созданный ответ
    }




/*    public function createReplyToReply(Request $request, Comment $comment, Comment $reply)
    {
        $validated = $request->validate([
            'user_name' => 'required|string|max:255',
            'avatar' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'home_page' => 'nullable|url',
            'captcha' => 'required|string',
            'text' => 'required|string',
            'rating' => 'nullable|integer',
            'quote' => 'nullable|string',
        ]);

        $replyToReply = $reply->replies()->create($validated);

        return response()->json($replyToReply, 201);
    }*/

/*    public function getRepliesToReply(Comment $comment, Comment $reply)
    {
        $repliesToReply = $reply->replies;
        return response()->json($repliesToReply);
    }*/

/*    public function uploadAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif', // Проверка на изображение
        ]);

    // Сохраняем аватар в public/images/avatars
    $path = $request->file('avatar')->store('images/avatars', 'public');

     // Возвращаем путь к аватару
     return response()->json(['path' => asset('storage/' . $path)], 201);
}*/



}
