<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Chat;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChatPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Chat $chat)
    {
        //
    }

    public function create(User $user, $friend_id)
    {
        return $user->friends()->where('users.id', $friend_id)->exists();
    }

    public function update(User $user, Chat $chat)
    {
        //
    }

    public function delete(User $user, Chat $chat)
    {
        //
    }
}
