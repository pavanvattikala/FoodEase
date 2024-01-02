<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderSubmittedToKitchen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $kot;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($kot)
    {
        $this->kot = $kot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('orderSubmitted');
    }

    public function broadcastWith()
    {

        return [
            'kot' => $this->kot,
        ];
    }
}
