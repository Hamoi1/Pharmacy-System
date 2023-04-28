<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserStatus implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }


    public function broadcastOn()
    {
        // jsut return data for $user->id
        // return new PrivateChannel('user-status.' . $this->user['id']);
        return ['user-status-' . $this->user->id];
    }

    public function broadcastAs()
    {
        return 'user-status';
    }
}
