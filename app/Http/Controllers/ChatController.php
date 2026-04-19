<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function show(User $seller)
    {
        abort_if($seller->role !== 'seller', 404);

        $messages = Chat::where(function ($query) use ($seller) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $seller->id);
        })
        ->orWhere(function ($query) use ($seller) {
            $query->where('sender_id', $seller->id)
                ->where('receiver_id', Auth::id());
        })
        ->orderBy('created_at')
        ->get();

        return view('chat.show', compact('seller', 'messages'));
    }

    public function store(Request $request, User $seller)
    {
        abort_if($seller->role !== 'seller', 404);

        $request->validate(['message' => 'required|string|max:1000']);

        Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $seller->id,
            'message' => $request->message,
        ]);

        return back()->with('success', 'Message sent to seller.');
    }
}
