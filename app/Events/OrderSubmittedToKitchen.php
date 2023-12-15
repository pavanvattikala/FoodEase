<?php

namespace App\Events;

use App\Helpers\KitchenHelper;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderSubmittedToKitchen implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $cart;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($cart)
    {
        $this->cart = $cart;
        $this->cart['kot'] = KitchenHelper::generateKOT();
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
        unset($this->cart['tableId']);
        unset($this->cart['total']);
        unset($this->cart['waiterId']);
        
        return [
            'cart' => $this->cart,
            // Add other properties as needed for Pusher
        ];
    }

}
