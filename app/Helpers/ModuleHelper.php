<?php

namespace App\Helpers;

use App\Http\Service\RestaurantService;

class ModuleHelper
{
    private $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }

    public function isModuleEnabled(string $module): bool
    {
        switch ($module) {
            case 'kitchen':
                return $this->restaurantService->isKitchenModuleEnabled();
            case 'waiter':
                return $this->restaurantService->isWaiterModuleEnabled();
            default:
                return false; // or throw an exception if the module is unknown
        }
    }

    public static function isKitchenModuleEnabled(): bool
    {
        // resolve the instance through Laravel container
        return app(self::class)->isModuleEnabled('kitchen');
    }

    public static function isWaiterModuleEnabled(): bool
    {
        return app(self::class)->isModuleEnabled('waiter');
    }

    public function getDisabledModules(): array
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
