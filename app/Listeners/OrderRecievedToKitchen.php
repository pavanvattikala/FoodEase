<?php

namespace App\Listeners;

use App\Events\OrderSubmittedToKitchen;
use App\Helpers\KitchenHelper;
use GuzzleHttp\Promise\Create;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
 


class OrderRecievedToKitchen
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(OrderSubmittedToKitchen $event)
    {
        $cart = $event->cart;
        // Step 1: Generate a KOT
        $kot = KitchenHelper::generateKOT();

        // Step 2: Create an order
        $order = Order::create([
            'tableNo'=>4,
            'KOT' => $kot,
            'total' => $cart['total'],
            // Add other fields as needed
        ]);

        // Step 3: Insert order details
        foreach ($cart as $key => $item) {
            if ($key !== 'total') {
                $orderDetail = new OrderDetail([
                    'item_name' => $item['name'], // Adjust field name based on your actual structure
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                // Associate order detail with the order
                $order->orderDetails()->save($orderDetail);
            }
        }

        // Optionally, you can return a response or redirect
        return response()->json(['message' => 'Order placed successfully']);
    }
}
