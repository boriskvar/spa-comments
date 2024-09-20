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
            $avatarPath = $request->file('avatar')->store('public/images/avatars');
        }

        $filePath = null;
        if ($request->hasFile('file_path')) {
            $filePath = $request->file('file_path')->store('public/files');
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
        $comment->avatar = $avatarPath;
        $comment->file_path = $filePath;
        $comment->save();

        return response()->json($comment, 201);
    }

    /**
     * Обновление существующего комментария.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Comment $comment)
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
    }

    /**
     * Удаление существующего комментария.
     *
     * @param \App\Models\Comment $comment
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Comment $comment)
    {
        $comment->delete();
        return response()->json(null, 204);
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
            'avatar' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'home_page' => 'nullable|url',
            'captcha' => 'required|string',
            'text' => 'required|string',
            'rating' => 'nullable|integer',
            'quote' => 'nullable|string',
        ]);

        $reply = $comment->replies()->create($validated);

        return response()->json($reply, 201);
    }


    public function createReplyToReply(Request $request, Comment $comment, Comment $reply)
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
    }

    public function getRepliesToReply(Comment $comment, Comment $reply)
    {
        $repliesToReply = $reply->replies;
        return response()->json($repliesToReply);
    }
}
