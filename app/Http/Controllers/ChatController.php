<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chat;

class ChatController extends Controller
{
public function index(Request $request)
{
    try {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
        ]);

        $chats = Chat::with(['sender', 'receiver'])
            ->where(function ($q) use ($request) {
                $q->where('sender_id', auth()->id())
                  ->where('receiver_id', $request->receiver_id);
            })
            ->orWhere(function ($q) use ($request) {
                $q->where('sender_id', $request->receiver_id)
                  ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at')
            ->get();

        $formattedChats = $chats->map(function ($chat) {
            return [
                'id' => $chat->id,
                'message' => $chat->message,
                'is_read' => (bool) $chat->is_read,
                'created_at' => $chat->created_at->format('Y-m-d H:i:s'),
                'sender' => [
                    'id' => $chat->sender->id ?? null,
                    'name' => $chat->sender->name ?? null,
                    'type' => $chat->sender->is_admin ? 'admin' : 'customer',
                ],
                'receiver' => [
                    'id' => $chat->receiver->id ?? null,
                    'name' => $chat->receiver->name ?? null,
                    'type' => $chat->receiver->is_admin ? 'admin' : 'customer',
                ],
            ];
        });

        return $this->sendResponse($formattedChats, 'Chat messages retrieved successfully.');
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
