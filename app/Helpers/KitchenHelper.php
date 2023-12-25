<?php

/**
 * File-level doc comment for KitchenHelper.php
 *
 * PHP version 7.4.3
 *
 * @category Helpers
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */

namespace App\Helpers;

use App\Models\Order;

/**
 * Class KitchenHelper
 * 
 * @category Helper
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */
class KitchenHelper
{

    /**
     * Generate a Kitchen Order Ticket (KOT) ID.
     *
     * @return string
     */
    public static function generateKOT()
    {
        $lastKOT = Order::latest('id')->first();

        $nextKOTId = $lastKOT ? $lastKOT->id + 1 : 1;

        $date = now()->format('Ymd');

        return "KOT-$date-$nextKOTId";
    }
}
