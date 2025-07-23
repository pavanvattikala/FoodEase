<?php

namespace App\Http\Service;

use App\Enums\OrderStatus;
use App\Models\Order;

class OrderSyncService extends Service
{

    public function hasNewOrders($lastOrderId, $waiterId)
    {
        $newOrdersExist = Order::where('id', '>', $lastOrderId)
            ->where('status', '!=', OrderStatus::New)
            ->where('waiter_id', $waiterId)
            ->exists();

        return $newOrdersExist;
    }
}
