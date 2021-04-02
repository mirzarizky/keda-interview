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
//        dd($receiver->isStaff());

        abort_if(
            $receiver->isStaff() && !$sender->isStaff(),
            403
        );

        $newMessage = Message::create([
           'message' => $request->message,
           'receiver_id' => $request->receiver_id,
           'sender_id'  => $sender->id
        ]);

        return response()->json($newMessage, 201);
    }
}
