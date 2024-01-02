<?php

/**
 * File-level doc comment for BillHelper.php
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

use App\Models\Bill;

/**
 * Class BillHelper
 *
 * @category Helper
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */
class BillHelper
{


    /**
     * Generate a unique bill ID.
     *
     * @return string
     */
    public static function generateBillID()
    {

        $datePart = now()->format('Ymd');

        $orderNumber = Bill::whereDate('created_at', now()->toDateString())
            ->count() + 1;

        $billId = $datePart . $orderNumber;

        return  $billId;
    }

    public static function printBill()
    {
        // handle print bill service
    }
}
