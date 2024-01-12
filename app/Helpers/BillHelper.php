<?php


namespace App\Helpers;

use App\Enums\OrderType;
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

    public static function createBill($orderType, $tableId, $kot, $notes, $paymentMethod, $discount)
    {
        $orders = null;

        if ($orderType == OrderType::Takeaway) {
            $orders = self::processPickUpBill($kot);
        }
        if ($orderType === OrderType::DineIn) {
            $orders = self::processTableBill($tableId);
        }

        $billData = collect([
            'orders' => $orders,
            'tableId' => $tableId,
            'notes' => $notes,
            'orderType' => $orderType,
            'discount' => $discount,
            'paymentMethod' => $paymentMethod,
        ]);


        $billId =  self::insertBill($billData);

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
        $orderType = $billData->get('orderType');
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

        if ($orderType == OrderType::DineIn) {
            $table = Table::find($tableId);
            $table->update([
                'status' => 'available',
                'taken_at' => null,
            ]);
        }
        return $bill->id;
    }
}
