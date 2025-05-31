<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Events\NewMessage;

class MessageController extends Controller
{
    public function index()
    {
        return Inertia::render('Messages', [
            'authUser' => auth()->user()
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $users = User::where('id', '!=', auth()->id())
            ->where(function($q) use ($query) {
                $q->where('name', 'like', "%$query%")
                  ->orWhere('email', 'like', "%$query%");
            })
            ->select('id', 'name', 'email')
            ->get();

        return response()->json($users);
    }

    public function fetchMessages($userId)
    {
        $messages = Message::where(function($q) use ($userId) {
                $q->where('sender_id', auth()->id())
                  ->where('receiver_id', $userId);
            })
            ->orWhere(function($q) use ($userId) {
                $q->where('sender_id', $userId)
                  ->where('receiver_id', auth()->id());
            })
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }

    public function send(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'body' => 'required|string|max:1000'
        ]);

        $message = Message::create([
            'sender_id' => auth()->id(),
            'receiver_id' => $request->receiver_id,
            'body' => $request->body
        ]);


        return response()->json($message);
    }
}