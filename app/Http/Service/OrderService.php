<?php

namespace App\Http\Service;

use App\Enums\OrderStatus;
use App\Helpers\KitchenHelper;
use App\Http\Service\Service;
use App\Models\Order;
use App\Models\OrderDetail;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderService extends Service
{

    // create order
    // return KOT
    public function createOrder($orderData)
    {
        // create Order
        $kot = KitchenHelper::generateKOT();
        $total = $orderData->get("total");
        $tableId = $orderData->get("tableId");
        $status = $orderData->get("status");
        $specialInstructions = $orderData->get("specialInstructions");
        $orderType = $orderData->get("orderType");
        $waiterId = $orderData->get("waiterId");
        $orderItems = $orderData->get("orderItems");


        $orderObject = [
            "KOT" => $kot,
            "total" => $total,
            "table_id" => $tableId,
            "status" => $status,
            "special_instructions" => $specialInstructions,
            "order_type" => $orderType,
            "waiter_id" => $waiterId
        ];

        Log::info("Preparing to create order: " . json_encode($orderObject));

        try {
            DB::beginTransaction();

            $order = Order::create($orderObject);

            foreach ($orderItems as $key => $item) {
                $orderDetail = new OrderDetail([
                    'order_id' => $order->id,
                    'menu_id' => $item["id"],
                    'quantity' => $item['quantity'],
                ]);

                $order->orderDetails()->save($orderDetail);
            }

            DB::commit();

            Log::info("Order created successfully: " . $order);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating order: " . $e->getMessage());
            return $this->errorResponse("error", "Error creating order", $e->getMessage());
        }

        return $this->successResponse("success", "Order created successfully", $kot);
    }

    public function markAsServed($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        $order->status = OrderStatus::Served;
        $order->save();

        return $order;
    }

    public function markAsClosed($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        $order->status = OrderStatus::Closed;
        $order->save();

        return $order;
    }

    public function markAsProcessing($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        $order->status = OrderStatus::Processing;
        $order->save();

        return $order;
    }

    public function markAsReadyForPickup($orderId)
    {
        $order = Order::find($orderId);

        if (!$order) {
            throw new Exception('Order not found');
        }

        $order->status = OrderStatus::ReadyForPickup;
        $order->save();

        return $order;
    }
}
