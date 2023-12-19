<?php

namespace App\Listeners;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Events\OrderSubmittedToKitchen;
use App\Helpers\KitchenHelper;
use GuzzleHttp\Promise\Create;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Session;
use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Table;

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
        $cart = collect($event->cart);

    
        $tableId = $cart->get("tableId");
        $waiterId = $cart->get("waiterId");
        $specialInstructions = $cart->get("special_instructions");
        $total = $cart->get("total");
        $kot = $cart->get("kot");



        // Step 2: Create an order
        $order = Order::create([
            'KOT' => $kot,
            'total' => $total,
            'table_id' => $tableId,
            'status' => OrderStatus::New->value,
            'special_instructions' => $specialInstructions,
            'order_type' => OrderType::DineIn->value, 
            'waiter_id' => $waiterId,
        ]);

        // Step 3: Insert order details
        foreach ($cart as $key => $item) {
            // Skip unwanted keys
            if ($key === 'total' || $key === 'tableId' || $key === 'waiterId' || $key === 'special_instructions') {
                continue;
            }
        
            // Check if $item is an array and has the 'quantity' key
            if (is_array($item) && isset($item['quantity'])) {
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

        Table::find($tableId)->update(['status' => TableStatus::Unavaliable]);

    }

}