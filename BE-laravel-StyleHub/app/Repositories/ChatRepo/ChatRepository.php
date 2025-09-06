<?php

namespace App\Repositories\ChatRepo;

use App\Events\NewMessage;
use App\Models\Chat;

class ChatRepository implements IChatRepository
{
    public function getAll($keyword = null)
    {

        $chats = Chat::with(['user', 'admin', 'messages'])
            ->when($keyword, function ($q) use ($keyword) {
                $q->whereHas('user', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%$keyword%")
                        ->orWhere('email', 'like', "%$keyword%");
                });
            })
            ->withCount('messages')
            ->withMax('messages', 'sent_at')
            ->orderByDesc('messages_max_sent_at')
            ->paginate(10);
        return $chats;
    }
    public function getAllMessagesByChatId($chat_id)
    {
        $chat = Chat::with(['user', 'messages'])->findOrFail($chat_id);
        return $chat;
    }
    public function sendMessages($data) {}
    public function getOrCreateChat($user_id)
    {
        $chat = Chat::where('user_id', $user_id)->first();

        if (!$chat) {
            $chat = Chat::create([
                'user_id' => $user_id,
                'started_at' => now()
            ]);
        }
        $messages = $chat->messages()->orderBy('sent_at', 'asc')->get();
        return [
            'chat' => $chat,
            'messages' => $messages
        ];
    }
    public function sendMessageFormUser($data)
    {
        $chatId = $data['chat_id'];
        $content = $data['content'];
        $chat = Chat::findOrFail($chatId);

        $message = $chat->messages()->create([
            'sender' => 'user',
            'content' => $content,
            'sent_at' => now()
        ]);

        return $message;
    }
    public function sendMessageFromAdmin($data)
    {
        $chatId = $data['chat_id'];
        $content = $data['content'];
        $chat = Chat::findOrFail($chatId);

        $message = $chat->messages()->create([
            'sender' => 'admin',
            'content' => $content,
            'sent_at' => now()
        ]);


        return $message;
    }
}
