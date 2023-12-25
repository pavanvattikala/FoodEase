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
}
