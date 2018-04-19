<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Message
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $chat_id
 * @property string $message
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereChatId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Message whereUserId($value)
 * @property-read \App\Models\Chat $chats
 * @property-read \App\Models\User $creator
 */
class Message extends Model
{
    const MESSAGE_PAGINATED_COUNT = 10;

    protected $fillable = ['user_id','chat_id','message'];

    public function chats() {
        return $this->belongsTo(Chat::class);
    }

    public function creator() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
