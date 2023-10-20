<?php

namespace App\Http\Controllers;

use App\Events\NewLiveChat;
use App\Models\LiveChat;
use Illuminate\Http\Request;

class AJAXController extends Controller
{
    public function sendLiveChat(Request $request)
    {
        if (auth()->user()) {
            $chat = $request->chat;
            try {
                LiveChat::create([
                    'chat' => $chat,
                    'user_id' => auth()->user()->id
                ]);
                return $this->customResponse(201, ['message' => 'Success']);
            } catch (\Throwable $th) {
                return $this->customResponse(500, ['message' => 'Error']);
            }
        }
        return $this->customResponse(401, ['message' => 'Unauthorized']);
    }

    public function poll(Request $request)
    {
        $latestCreatedAt = LiveChat::latest()->first();
        $latestChatId = $latestCreatedAt->id;

        if (session('latestChatId') != $latestChatId) {
            $data = LiveChat::where('id', '>', $latestChatId)->get();
            session(['latestChatId' => $latestChatId]);
            return response()->json($data);
        }
        return response()->json();
    }
}
