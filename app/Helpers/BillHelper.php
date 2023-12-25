<?php

namespace App\Helpers;

use App\Models\Bill;
use App\Models\Order;
use Carbon\Carbon;
use Ramsey\Uuid\Uuid;

class BillHelper{

    public static function generateBillID()
    {

        $datePart = now()->format('Ymd');        

        $orderNumber = Bill::whereDate('created_at', now()->toDateString())->count() + 1;
        
        $billId = $datePart . $orderNumber;

        return  $billId;
    }
}

?>

