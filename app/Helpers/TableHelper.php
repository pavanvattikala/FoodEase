<?php

namespace App\Helpers;

use App\Models\Table;
use App\Enums\TableStatus;

class TableHelper{
    public static function checkIfTableAvailable($tableId){

        $status = Table::where('id',$tableId)->first()->status->value;

        if($status==TableStatus::Available->value){

            return true;
        }

        return false;
    }
}

?>