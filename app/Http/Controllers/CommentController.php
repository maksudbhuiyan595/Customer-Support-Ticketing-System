<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequst;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        try {
            $comments = Comment::with(['user'])->where('user_id', auth()->id())->orderByDesc('id')->get();
            return $this->sendResponse($comments, 'Comments retrieved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }
    public function store(CommentRequst $request)
    {
        try {
            $validated = $request->validated();
            $comment = Comment::create([
                'user_id' => auth()->id(),
                'ticket_id' => $validated['ticket_id'],
                'comment' => $validated['comment'],
            ]);
            return $this->sendResponse($comment, 'Comment created successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred: ' . $e->getMessage(), [], 500);
        }
    }   
}
