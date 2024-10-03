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

     public function index(Request $request)
     {
         // Получаем параметры сортировки и пагинации из запроса
         $sort = $request->input('sort', 'created_at');  // Поле для сортировки, по умолчанию 'created_at'
         $order = $request->input('order', 'desc');      // Порядок сортировки, по умолчанию 'desc'
         $perPage = $request->input('per_page', 10);     // Количество элементов на страницу, по умолчанию 10
         $page = $request->input('page', 1);             // Текущая страница

         // Выполняем запрос к базе данных с сортировкой и пагинацией
         $comments = Comment::whereNull('parent_id')
             ->with('replies')
             ->orderBy($sort, $order)  // Сортируем по полю (например, created_at), в порядке (asc или desc)
             ->paginate($perPage, ['*'], 'page', $page); // Пагинация

         // Возвращаем результат в формате JSON
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

}
