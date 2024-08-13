<?php

namespace App\Helpers;

use App\Models\Table;
use App\Enums\TableStatus;


class ModuleHelper
{

    public static function isKitchenModuleEnabled()
    {
        return boolval(env('KITCHEN_MODULE_ENABLED', false));
    }

    public static function isWaiterModuleEnabled()
    {
        return boolval(env('WAITER_MODULE_ENABLED ', false));
    }

    public static function getDiabledModules()
    {
        $modules = [];
        if (!self::isKitchenModuleEnabled()) {
            $modules[] = 'Kitchen';
        }
        if (!self::isWaiterModuleEnabled()) {
            $modules[] = 'Waiter';
        }
        return $modules;
    }
}
