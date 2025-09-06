<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;


// // Cho user FE React (token API)
// Broadcast::routes(['middleware' => 'web']);

// Broadcast::routes(['middleware' => ['auth:api']]);

// Cho admin Blade (session)
// Broadcast::routes(['middleware' => ['auth:web']]);

Broadcast::channel('chat.{chatId}', function ($user, $chatId) {
    $chat = Chat::find($chatId);

    return $chat && ($user->id === $chat->user_id || $user->role === 'admin');
});
