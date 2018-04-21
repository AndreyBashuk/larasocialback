<?php

namespace App\Models\Traits\User;

use Illuminate\Support\Facades\DB;

trait ChatInteraction
{
    public function canJoinChat($chat_id) {
        return DB::table('chat_user')->where([
            'chat_id' => $chat_id,
            'user_id' => $this->id
        ])->exists();
    }
}