<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Chat
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $is_conversation
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Chat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Chat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Chat whereIsConversation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Chat whereUpdatedAt($value)
 */
class Chat extends Model
{
    public function users() {
        return $this->belongsToMany(User::class,'chat_user');
    }

    public function messages() {
        return $this->hasMany(Message::class);
    }
}
