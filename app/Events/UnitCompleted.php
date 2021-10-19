<?php

namespace App\Events;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class UnitCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $user;
    public $unit;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Unit $unit)
    {
        $this->user = $user;
        $this->unit = $unit;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}