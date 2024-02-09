<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    //
    public function index()
    {
        $newOrders = Order::with('orderDetails.menu')->with('orderDetails.menu.category')
            ->with('waiter')
            ->where('status', OrderStatus::New->value)
            ->orderBy('created_at')
            ->get();

        $processingOrders = Order::with('orderDetails.menu')->with('orderDetails.menu.category')
            ->with('waiter')
            ->where('status', OrderStatus::Processing->value)
            ->orderBy('created_at')
            ->get();


        return view("kitchen.index", compact('newOrders', 'processingOrders'));
    }

    public function acceptOrder(Request $request)
    {

        $orderId = $request->orderId;


        // Retrieve the order from the database
        $order = Order::find($orderId);

        if (!$order) {
            // Handle the case where the order is not found
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = OrderStatus::Processing->value;

        $order->save();

        // You can return a response if needed
        return response()->json(['message' => 'Order accepted successfully']);
    }

    public function discardOrder(Request $request)
    {

        $orderId = $request->orderId;


        // Retrieve the order from the database
        $order = Order::find($orderId);

        if (!$order) {
            // Handle the case where the order is not found
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->delete();

        // You can return a response if needed
        return response()->json(['message' => 'Order deleted successfully']);
    }


    public function getNewOrderComponent(Request $request)
    {

        $KOT = $request->kot;

        $order = Order::with('orderDetails.menu')
            ->with('waiter')
            ->with('table')
            ->where('KOT', $KOT)
            ->orderBy('created_at')
            ->first();

        $html = view('components.order.new-order-component-for-kitchen', compact('order'))->render();

        return response()->json(['html' => $html]);
    }

    public function completeOrder(Request $request)
    {

        $orderId = $request->orderId;


        // Retrieve the order from the database
        $order = Order::find($orderId);

        if (!$order) {
            // Handle the case where the order is not found
            return response()->json(['error' => 'Order not found'], 404);
        }

        $order->status = OrderStatus::ReadyForPickup->value;

        $order->save();

        // You can return a response if needed
        return response()->json(['message' => 'Order Completed successfully']);
    }
}
