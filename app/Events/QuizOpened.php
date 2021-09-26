<?php

namespace App\Events;

use App\Models\Quiz;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class QuizOpened
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $user;
    public $quiz;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user, Quiz $quiz)
    {
        $this->user = $user;
        $this->quiz = $quiz;
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
