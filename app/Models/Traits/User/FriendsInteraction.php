<?php

namespace App\Models\Traits\User;

trait FriendsInteraction
{
    public function addToFriends($friend)
    {
        $this->friends()->attach($friend->id);
        $friend->friends()->attach($this->id);
    }

    public function removeFromFriends($friend)
    {
        $this->friends()->detach($friend->id);
    }
}