<?php

namespace App\Models\Traits\User;

use App\Models\User;

trait FriendsInteraction
{
    public function addToFriends($friend)
    {
        $this->requestedFriends()->attach($friend->id, ['status' => User::FRIENDSHIP_STATUS['ACCEPT']]);
        $friend->requestedFriends()->updateExistingPivot($this->id, ['status' => User::FRIENDSHIP_STATUS['ACCEPT']]);
    }

    public function removeFromFriends($friend)
    {
        $this->requestedFriends()->detach($friend->id);
    }

    public function sendFriendshipRequest($friend)
    {
        $this->requestedFriends()->attach($friend->id, ['status' => User::FRIENDSHIP_STATUS['REQUEST']]);
    }
}