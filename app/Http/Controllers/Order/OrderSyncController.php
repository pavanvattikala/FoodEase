<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderSyncController extends Controller
{

    public function syncPendingOrder(Request $request)
    {
        $lastOrderId = $request->lastOrderId;
        $waiterId = Auth::id();

        $latestOrders = Order::where('id', '>', $lastOrderId)
            ->where('status', '!=', OrderStatus::Closed)
            ->where('waiter_id', $waiterId)
            ->orderBy('created_at', 'desc')
            ->get();

        $noOrders = false;

        $html = "";

        foreach ($latestOrders as $order) {
            $currentHtml = view('components.order-component-for-waiter', compact('order'))->render();

            $html .= $currentHtml;
            $noOrders = true;
        }


        // Use response() for consistent JSON responses
        return response()->json(["newOrders" => $noOrders, 'html' => $html]);
    }

    public function syncPickUpOrder(Request $request)
    {
        $lastOrderId = $request->lastOrderId;
        $waiterId = Auth::id();

        $latestOrders = Order::where('id', '>', $lastOrderId)
            ->where('status', OrderStatus::ReadyForPickup)
            ->where('waiter_id', $waiterId)
            ->orderBy('created_at', 'desc')
            ->get();

        $noOrders = false;

        $html = "";

        foreach ($latestOrders as $order) {
            $currentHtml = view('components.order-component-for-waiter', compact('order'))->render();

            $html .= $currentHtml;
            $noOrders = true;
        }


        // Use response() for consistent JSON responses
        return response()->json(["newOrders" => $noOrders, 'html' => $html]);
    }
}
