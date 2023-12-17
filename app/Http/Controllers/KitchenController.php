<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    //
    public function index(){
        $newOrders = Order::with('orderDetails.menu') // Adjust the relationship names based on your actual structure
            ->with('waiter')
            ->where('status',OrderStatus::New->value)
            ->orderBy('created_at')
            ->get();

        $processingOrders = Order::with('orderDetails.menu') // Adjust the relationship names based on your actual structure
        ->with('waiter')
        ->where('status',OrderStatus::Processing->value)
        ->orderBy('created_at')
        ->get();

        
        return view("kitchen.index",compact('newOrders','processingOrders'));
    }

    public function acceptOrder(Request $request){

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

    public function discardOrder(Request $request){

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
}
