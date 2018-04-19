<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

use App\Models\Chat;

Broadcast::channel('App.User.{id}', function ($user, $id) {
    Log::info('Hell yeah');
    return true;
});

Broadcast::channel('chat.{chat}', function ($user, $chat_id) {
   if($user->canJoinChat($chat_id)) {
       return $user;
   }
});