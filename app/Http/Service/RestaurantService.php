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
        // returns true if biller printer is set
        return !is_null($this->getRestaurantDetails()->biller_printer);
    }
    public function isKOTPrintEnabled()
    {
        // returns true if kitchen printer is set
        return !is_null($this->getRestaurantDetails()->kitchen_printer);
    }

    public function getKitchenPrinter()
    {
        return $this->getRestaurantDetails()->kitchen_printer ?? config('predefined_options.printer.kitchen');
    }

    public function getBillerPrinter()
    {
        return $this->getRestaurantDetails()->biller_printer ?? config('predefined_options.printer.biller');
    }
}
