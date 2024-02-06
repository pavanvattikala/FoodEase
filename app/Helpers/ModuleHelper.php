<?php

namespace App\Helpers;

use App\Models\Table;
use App\Enums\TableStatus;


class ModuleHelper
{

    public static function isKitchenModuleEnabled()
    {

        return true;
    }

    public static function isWaiterModuleEnabled()
    {

        return true;
    }
}
