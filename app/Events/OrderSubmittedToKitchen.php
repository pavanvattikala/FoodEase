<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

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
        Log::info("OrderSubmittedToKitchen event fired with KOT: " . $kot);
        $this->kot = $kot;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('order-submitted-to-kitchen'),
        ];
    }

    public function broadcastAs(): string
    {

        return 'OrderSubmittedToKitchen';
    }
}
