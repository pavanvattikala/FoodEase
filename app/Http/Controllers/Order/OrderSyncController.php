<?php

namespace App\Http\Controllers\Order;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Service\OrderSyncService;

class OrderSyncController extends Controller
{
    private $orderSyncService;

    public function __construct(OrderSyncService $orderSyncService)
    {
        $this->orderSyncService = $orderSyncService;
    }


    public function syncPendingOrder(Request $request)
    {
        $lastOrderId = $request->lastOrderId;

        /** @var \App\User */
        $user = Auth::user();

        $hasNewOrders = $this->orderSyncService->hasNewOrders($lastOrderId, $user->id);

        if ($hasNewOrders) {
            return response()->json([
                "hasNewOrders" => $hasNewOrders,
                "status" => "success",
                "message" => "New Orders Found"
            ], 200);
        } else {
            return response()->json([
                "hasNewOrders" => $hasNewOrders,
                "status" => "success",
                "message" => "No New Orders Found"
            ], 200);
        }
    }
}
