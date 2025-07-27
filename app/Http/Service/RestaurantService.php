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
}
