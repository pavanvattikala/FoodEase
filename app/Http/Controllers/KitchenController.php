<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    //
    public function index(){
        $orders = Order::with('orderDetails.menu') // Adjust the relationship names based on your actual structure
            ->with('waiter')
            ->where('status',OrderStatus::New->value)
            ->orderBy('created_at')
            ->get();

        
        return view("kitchen.index",compact('orders'));
    }
}
