<?php

/**
 * File-level doc comment for TableHelper.php
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

use App\Models\Table;
use App\Enums\TableStatus;
use App\Models\Bill;
use App\Models\BillOrder;
use App\Models\Order;

/**
 * Class TableHelper
 *
 * @category Helper
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */
class TableHelper
{
    /**
     * Check if a table is available.
     * 
     * @param int $tableId The ID of the table.
     * 
     * @return bool Returns true if the table is available, false otherwise.
     */
    public static function checkIfTableAvailable($tableId)
    {

        $status = Table::where('id', $tableId)->first()->status->value;

        if ($status == TableStatus::Available->value) {

            return true;
        }

        return false;
    }

    public static function addTableToSession($tableId)
    {

        $tableName = Table::where('id', $tableId)->first()->name;

        $tableData = [
            'tableId' => $tableId,
            'tableName' => $tableName,
        ];

        Session()->put("tableData", $tableData);
    }

    public static function markTableAsPrinted($tableId)
    {
        $table = Table::where('id', $tableId)->first();

        $table->status = TableStatus::Printed;

        $table->save();
    }

    public static function markTableAsPaid($tableId)
    {
        $table = Table::where('id', $tableId)->first();

        $table->status = TableStatus::Available;

        $table->taken_at = null;

        $table->save();
    }

    public static function markTableAsRunning($tableId)
    {
        $table = Table::where('id', $tableId)->first();

        $table->status = TableStatus::Running;

        $table->save();
    }

    public static function markTableAsUnavaliable($tableId)
    {
        $table = Table::where('id', $tableId)->first();

        $table->status = TableStatus::Unavaliable;

        $table->save();
    }

    public static function markTableAsTaken($tableId)
    {
        $table = Table::where('id', $tableId)->first();

        $table->status = TableStatus::Running;

        $table->taken_at = now();

        $table->save();
    }
}
