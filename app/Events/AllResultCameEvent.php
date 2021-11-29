<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AllResultCameEvent implements shouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $gameID;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($gameID)
    {
        $this->gameID = $gameID;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('AllResultCame');
    }
    public function broadcastWith()
    {
        return ['gameID' => $this->gameID];
    }
}
