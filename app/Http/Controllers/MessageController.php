<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();
        
        // Get users that have chatted with the current user
        $contacts = User::whereHas('sentMessages', function ($query) use ($userId) {
            $query->where('receiver_id', $userId);
        })->orWhereHas('receivedMessages', function ($query) use ($userId) {
            $query->where('sender_id', $userId);
        })->where('id', '!=', $userId)->get();

        $activeContact = null;
        if ($request->has('user')) {
            $activeContact = User::find($request->user);
        }

        return view('chat.index', [
            'title' => 'Pesan',
            'contacts' => $contacts,
            'activeContact' => $activeContact,
        ]);
    }

    public function fetchMessages(User $user)
    {
        $userId = Auth::id();

        // Mark messages as read
        Message::where('sender_id', $user->id)
            ->where('receiver_id', $userId)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        $messages = Message::where(function ($query) use ($userId, $user) {
            $query->where('sender_id', $userId)->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($userId, $user) {
            $query->where('sender_id', $user->id)->where('receiver_id', $userId);
        })->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'is_read' => false,
        ]);

        return response()->json($message);
    }
}
