<?php

namespace App\Http\Service;

use App\Models\Restaurant;

class RestaurantService extends Service
{
    public function getRestaurantDetails()
    {
        return Restaurant::getCachedRestaurants()->first();
    }
}
