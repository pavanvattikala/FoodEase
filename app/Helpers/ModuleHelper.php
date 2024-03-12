<?php

namespace App\Helpers;

use App\Models\Table;
use App\Enums\TableStatus;


class ModuleHelper
{

    public static function isKitchenModuleEnabled()
    {
        return env('KITCHEN_MODULE_ENABLED', false);
    }

    public static function isWaiterModuleEnabled()
    {
        return env('WAITER_MODULE_ENABLED ', false);
    }
}
