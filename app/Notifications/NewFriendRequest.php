<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewFriendRequest extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;

    /**
     * NewFriendRequest constructor.
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    public function via($notifiable)
    {
        return ['broadcast', 'database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'user' => $this->user
        ];
    }

    public function toBroadcast($notifiable)
    {
        return (new BroadcastMessage([
            'data' => [
                'user' => $this->user
            ]
        ]))->onQueue('NewFriendRequest');
    }


}
