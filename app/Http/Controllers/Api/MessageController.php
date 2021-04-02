<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Send message to other customer/staff
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'required',
        ]);

        $sender = auth()->user();
        $receiver = User::findOrFail($request->receiver_id);

        abort_if(
            $receiver->isStaff() && !$sender->isStaff(),
            403,
            'You don\'t have permission to access this resource.'
        );

        $newMessage = Message::create([
            'message' => $request->message,
            'receiver_id' => $request->receiver_id,
            'sender_id' => $sender->id
        ]);

        return response()->json($newMessage, 201);
    }

    public function index()
    {
        $user = auth()->user();

        $messages = Message::where(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->orWhere('receiver_id', $user->id);
        })
            ->get();

        return response()->json($messages);
    }

    public function getAllMessages()
    {
        $messages = Message::all();

        return response()->json($messages);
    }
}
