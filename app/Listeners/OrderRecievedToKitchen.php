<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
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
    return response()->json(['message' => 'Order placed successfully']);

    $cart = collect($event->cart);

   

    $tableId = $cart->get("tableId");
    $waiterId = $cart->get("waiterId");
    $specialInstructions = $cart->get("special_instructions");
    $total = $cart->get("total");
    

    // Step 1: Generate a KOT
    $kot = KitchenHelper::generateKOT();

    // Step 2: Create an order
    $order = Order::create([
        'KOT' => $kot,
        'total' => $total,
        'table_id' => $tableId,
        'status' => OrderStatus::New,
        'special_instructions' => $specialInstructions,
        'order_type' => OrderType::DineIn, 
        'waiter_id' => $waiterId,
    ]);

    // Step 3: Insert order details
    foreach ($cart as $key => $item) {
        if ($key !== 'total' && $key !== 'tableId' && $key !== 'waiterId' && $key !== 'special_instructions') {
            $orderDetail = new OrderDetail([
                'order_id' => $order->id,
                'menu_id' => $key,
                'quantity' => $item['quantity'],
                // Add other details as needed
            ]);

            // Associate order detail with the order
            $order->orderDetails()->save($orderDetail);
        }
    }

    // Optionally, you can return a response or redirect
    return response()->json(['message' => 'Order placed successfully']);
}

}
