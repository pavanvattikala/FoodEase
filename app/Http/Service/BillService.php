<?php

namespace App\Http\Service;

use App\Helpers\KitchenHelper;

class BillService extends Service
{
    public function generateBill($order)
    {
        $bill = [
            "order" => $order,
            "total" => $order->total,
            "tax" => 0.18 * $order->total,
            "discount" => 0.05 * $order->total,
            "grandTotal" => $order->total + 0.18 * $order->total - 0.05 * $order->total
        ];

        return $bill;
    }
}
