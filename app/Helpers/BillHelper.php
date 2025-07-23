<?php


namespace App\Helpers;

use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Models\Bill;
use App\Models\BillOrder;
use App\Models\Order;
use App\Models\Table;

class BillHelper
{

    public static function generateBillID()
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $datePart = now()->format('Ymd');

        $orderNumber = Bill::whereDate('created_at', '>=', $todayStart)->whereDate('created_at', '<=', $todayEnd)->count() + 1;

        $billId = $datePart . $orderNumber;

        return  $billId;
    }

    public static function createPickUpBill($kot, $notes, $paymentMethod, $discount)
    {
        $orders = self::processPickUpBill($kot);

        $billData = collect([
            'orders' => $orders,
            'notes' => $notes,
            'orderType' => OrderType::Takeaway,
            'discount' => $discount,
            'paymentMethod' => $paymentMethod,
        ]);

        $billId =  self::insertBill($billData);

        return $billId;
    }

    public static function createTableBill($tableId, $notes, $paymentMethod, $discount)
    {
        // Check if there's an existing open bill for this table
        $existingBill = Bill::where('table_id', $tableId)->where('status', 'open')->first();

        $orders = self::processTableBill($tableId);

        if ($existingBill) {
            // Update the existing bill with new orders
            self::updateExistingBill($existingBill, $orders, $notes, $discount);
            return $existingBill->id;
        } else {
            // If no open bill, create a new one
            $billData = collect([
                'tableId' => $tableId,
                'orders' => $orders,
                'notes' => $notes,
                'orderType' => OrderType::DineIn,
                'discount' => $discount,
                'paymentMethod' => $paymentMethod,
            ]);

            $billId =  self::insertBill($billData);

            TableHelper::markTableAsPrinted($tableId);

            return $billId;
        }
    }
    public static function updateExistingBill($existingBill, $newOrders, $notes, $discount)
    {
        // Add new orders to the existing bill
        foreach ($newOrders as $order) {
            // Ensure that the new orders are not already associated with the bill
            $alreadyBilled = BillOrder::where('bill_id', $existingBill->id)
                ->where('order_id', $order->id)
                ->exists();

            if (!$alreadyBilled) {
                BillOrder::create([
                    'bill_id' => $existingBill->id,
                    'order_id' => $order->id,
                ]);
            }
        }

        // Recalculate totals
        $currentOrders = $existingBill->orders;
        $newTotal = $currentOrders->sum('total');
        $grandTotal = $newTotal - $discount;

        // Update the bill with new totals and notes
        $existingBill->update([
            'bill_amount' => $newTotal,
            'grand_total' => $grandTotal,
            'discount' => $discount,
            'notes' => $notes,
        ]);
    }

    public static function processPickUpBill($kot)
    {
        $orders = Order::where('kot', $kot)->get();
        return $orders;
    }

    public static function processTableBill($tableId)
    {
        $table = Table::find($tableId);
        $takenTime = $table->taken_at;

        // Get only orders that are not already included in any bill
        $openBill = Bill::where('table_id', $tableId)->where('status', 'open')->first();
        if ($openBill) {
            // Exclude orders already associated with the open bill
            $billedOrders = BillOrder::where('bill_id', $openBill->id)->pluck('order_id')->toArray();

            $orders = Order::where('table_id', $tableId)
                ->where('created_at', '>=', $takenTime)
                ->whereNotIn('id', $billedOrders)
                ->get();
        } else {
            // If no open bill, return all orders
            $orders = Order::where('table_id', $tableId)
                ->where('created_at', '>=', $takenTime)
                ->get();
        }

        return $orders;
    }
    private static function insertBill($billData)
    {
        $tableId = $billData->get('tableId');
        $paymentMethod = $billData->get('paymentMethod');
        $notes = $billData->get('notes');
        $discount = $billData->get('discount');
        $orders = $billData->get('orders');
        $total = $orders->sum('total');
        $grandTotal = $total - $discount;

        $billId = BillHelper::generateBillID();

        $billObject = [
            'bill_id' => $billId,
            'table_id' => $tableId,
            'bill_amount' => $total,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'payment_method' => $paymentMethod,
            'notes' => $notes,
        ];

        $bill = Bill::create($billObject);

        foreach ($orders as $order) {
            BillOrder::create([
                'bill_id' => $bill->id,
                'order_id' => $order->id,
            ]);

            // $order->update([
            //     'status' => 'closed',
            // ]);
        }
        return $bill->id;
    }

    public static function getBillOrders($billId)
    {
        $billDetails = Bill::where('id', $billId)
            ->with('orders')
            ->with('orders.orderDetails')
            ->with('orders.orderDetails.menu')
            ->with('table')
            ->first();


        $orderDetails = collect([]);

        foreach ($billDetails->orders as $order) {
            foreach ($order->orderDetails as $orderDetail) {
                $itemName = $orderDetail->menu->name;
                $quantity = $orderDetail->quantity;
                $price = $orderDetail->menu->price;

                if ($orderDetails->has($itemName)) {
                    // Retrieve current values
                    $currentDetails = $orderDetails->get($itemName);

                    // Update values
                    $currentDetails['quantity'] += $quantity;
                    $currentDetails['price'] = $price;
                    $currentDetails['total'] = $currentDetails['quantity'] * $currentDetails['price'];

                    // Put updated values back
                    $orderDetails->put($itemName, $currentDetails);
                } else {
                    $orderDetails->put($itemName, [
                        'quantity' => $quantity,
                        'price' => $price,
                        'total' => $quantity * $price
                    ]);
                }
            }
        }

        return $orderDetails;
    }

    public static function getLatestBillId($tableId)
    {
        $bill = Bill::where('table_id', $tableId)->latest()->first();
        return $bill->id;
    }
}
