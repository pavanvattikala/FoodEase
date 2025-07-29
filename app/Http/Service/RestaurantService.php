<?php

namespace App\Http\Service;

use App\Models\Restaurant;

class RestaurantService extends Service
{
    public function getRestaurantDetails()
    {
        return Restaurant::getCachedRestaurants()->first();
    }

    public function isRestaurantConfigured()
    {
        $restaurant = $this->getRestaurantDetails();
        return !is_null($restaurant) && !is_null($restaurant->name) && !is_null($restaurant->address);
    }

    public function isKitchenEnabled()
    {
        return (bool)$this->getRestaurantDetails()->kitchen_module_enabled ?? false;
    }

    public function isWaiterEnabled()
    {
        return (bool)$this->getRestaurantDetails()->waiter_module_enabled ?? false;
    }

    public function isPrintBillEnabled()
    {
        //return (bool)$this->getRestaurantDetails()->bill_print_enabled ?? false;
        return true;
    }
    public function isKOTPrintEnabled()
    {
        //return (bool)$this->getRestaurantDetails()->kot_print_enabled ?? false;
        return true;
    }
}
