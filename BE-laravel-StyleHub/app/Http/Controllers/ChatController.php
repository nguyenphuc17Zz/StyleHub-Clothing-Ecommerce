<?php

namespace App\Http\Controllers;

use App\Events\NewMessage;
use App\Models\Chat;
use App\Services\ChatService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChatController extends Controller
{
    //
    protected $chatService;
    public function __construct(ChatService $chatService)
    {
        $this->chatService = $chatService;
    }
    public function index(Request $request)
    {
        $chats = $this->chatService->getAll($request['keyword']);
        return view("chats.index", data: compact('chats'));
    }
    public function show($id)
    {
        $chat = $this->chatService->getAllMessagesByChatId($id);
        return view("chats.show", data: compact('chat'));
    }
    public function sendMessageFromAdmin(Request $request, $chat_id)
    {
        $data = [
            'chat_id' => $chat_id,
            'content' => $request->message,
        ];

        $message = $this->chatService->sendMessageFromAdmin($data);
        broadcast(new NewMessage($message));
        Log::info($message);

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    // API
    public function sendMessageFormUser(Request $request, $chat_id)
    {
        $data = [
            'chat_id' => $chat_id,
            'content' => $request->input,
        ];
        $message = $this->chatService->sendMessageFormUser($data);
        broadcast(new NewMessage($message));
        Log::info($message);
        return response()->json($message);
    }
    public function getOrCreateChat(Request $request)
    {
        $user = $request->user();
        $user_id = $user->id;
        $result = $this->chatService->getOrCreateChat($user_id);
        return response()->json([
            'success' => true,
            'messages' => $result["messages"],
            "chat" => $result["chat"],
        ]);
    }
}
