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
        $orders = self::processTableBill($tableId);

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

    public static function processPickUpBill($kot)
    {
        $orders = Order::where('kot', $kot)->get();
        return $orders;
    }
    public static function processTableBill($tableId)
    {
        $table = Table::find($tableId);
        $takenTime = $table->taken_at;

        $orders = Order::where('table_id', $tableId)->where('created_at', '>=', $takenTime)->get();
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

            $order->update([
                'status' => 'closed',
            ]);
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
                    $orderDetails[$itemName]['quantity'] += $quantity;
                    $orderDetails[$itemName]['price'] += $price;
                    $orderDetails[$itemName]['total'] = $orderDetails[$itemName]['quantity'] * $orderDetails[$itemName]['price'];
                } else {
                    $orderDetails->put($itemName, ['quantity' => $quantity, 'price' => $price, 'total' => $quantity * $price]);
                }
            }
        }
        return $orderDetails;
    }
}
