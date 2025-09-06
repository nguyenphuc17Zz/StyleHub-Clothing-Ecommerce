<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    //
    protected $fillable = ['chat_id', 'sender', 'content', 'sent_at'];
    protected $casts = [
        'sent_at' => 'datetime',
    ];
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
