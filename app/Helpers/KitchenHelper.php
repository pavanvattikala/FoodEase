<?php

namespace App\Helpers;

use App\Models\Order;
use Ramsey\Uuid\Uuid;

class KitchenHelper{

    public static function generateKOT()
    {
        $lastKOT = Order::latest('id')->first();

        // Increment the last KOT ID or start from 1 if there are no existing KOTs
        $nextKOTId = $lastKOT ? $lastKOT->id + 1 : 1;

        $date = now()->format('Ymd');

        return "KOT-$date-$nextKOTId";
    }
}

?>

