<?php

namespace App\Helpers;

use App\Models\Table;
use App\Enums\TableStatus;


class ModuleHelper
{

    public static function isKitchenModuleEnabled()
    {
        // check if pusher values are given
        if (config('broadcasting.connections.pusher.key') && config('broadcasting.connections.pusher.secret') && config('broadcasting.connections.pusher.app_id')) {
            return true;
        }
        return false;
    }

    public static function isWaiterModuleEnabled()
    {
        return false;
    }
}
