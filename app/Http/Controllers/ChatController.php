<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
public function index(Request $request)
{
    try {
        $user = $request->user(); // Get authenticated user

        $messages = Chat::with('sender')
            ->where('receiver_id', $user->id)
            ->orWhere('sender_id', $user->id)
            ->latest()
            ->get();


        return $this->sendResponse($messages, 'Chat messages retrieved successfully.');
    } catch (\Exception $e) {
        return $this->sendError('Failed to fetch chat messages.', ['error' => $e->getMessage()], 500);
    }
}

public function store(Request $request)
{
    try {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'receiver_type' => 'required|in:admin,customer',
            'message' => 'required|string',
        ]);

        $chat = Chat::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'sender_type' => auth()->user()->is_admin ? 'admin' : 'customer',
            'receiver_type' => $request->receiver_type,
            'message' => $request->message,
        ]);

        return $this->sendResponse($chat, 'Message sent successfully.');
    } catch (\Exception $e) {
        return $this->sendError('Failed to send message.', ['error' => $e->getMessage()], 500);
    }
}
public function markAsRead(Request $request)
{
    try {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);
        Chat::where('sender_id', $request->receiver_id)
            ->where('receiver_id', auth()->id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return $this->sendResponse([], 'Chats marked as read.');
    } catch (\Exception $e) {
        return $this->sendError('Failed to mark chats as read.', ['error' => $e->getMessage()], 500);
    }
}
}
