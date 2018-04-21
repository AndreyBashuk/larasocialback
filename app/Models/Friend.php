<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Friend extends Model
{
    public function user() {
        return $this->belongsToMany(User::class, 'friend_user');
    }
}
