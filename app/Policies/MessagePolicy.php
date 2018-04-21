<?php

namespace App\Policies;

use App\Models\Chat;
use App\Models\User;
use App\Models\Message;
use Illuminate\Auth\Access\HandlesAuthorization;

class MessagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the message.
     *
     * @param User $user
     * @param Message $message
     * @return mixed
     */
    public function view(User $user, $chat_id)
    {
        return Chat::with('users')->findOrFail($chat_id)->users->pluck('id')->contains($user->id);
    }

    /**
     * Determine whether the user can create messages.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user, $chat_id)
    {
        return Chat::with('users')->findOrFail($chat_id)->users->pluck('id')->contains($user->id);
    }

    /**
     * Determine whether the user can update the message.
     *
     * @param User $user
     * @param Message $message
     * @return mixed
     */
    public function update(User $user, Message $message, $chat_id)
    {
        return Chat::with('users')->findOrFail($chat_id)->users->pluck('id')->contains($user->id);
    }

    /**
     * Determine whether the user can delete the message.
     *
     * @param User $user
     * @param Message $message
     * @return mixed
     */
    public function delete(User $user, Message $message, $chat_id)
    {
        return Chat::with('users')->findOrFail($chat_id)->users->pluck('id')->contains($user->id);
    }
}
