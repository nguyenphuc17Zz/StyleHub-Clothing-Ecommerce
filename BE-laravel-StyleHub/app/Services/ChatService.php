<?php

namespace App\Services;

use App\Repositories\ChatRepo\ChatRepository;

class ChatService
{
    protected $chatRepo;
    public function __construct(ChatRepository $chatRepo)
    {
        $this->chatRepo = $chatRepo;
    }
    public function getAll($keyword = null)
    {
        return $this->chatRepo->getAll($keyword);
    }
    public function sendMessageFormUser($data)
    {
        return $this->chatRepo->sendMessageFormUser($data);
    }
    // public function sendMessage($data)
    // {
    //     return $this->chatRepo->sendMessage($data);
    // }
    public function getOrCreateChat($user_id)
    {
        return $this->chatRepo->getOrCreateChat($user_id);
    }
    public function getAllMessagesByChatId($chat_id)
    {
        return $this->chatRepo->getAllMessagesByChatId($chat_id);
    }
    public function sendMessageFromAdmin($data)
    {
        return $this->chatRepo->sendMessageFromAdmin($data);    
    }
}
