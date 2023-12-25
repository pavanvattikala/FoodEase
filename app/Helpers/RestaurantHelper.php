<?php

namespace App\Helpers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

class RestaurantHelper
{
    protected static function refreshRestaurantDetails()
    {
        return Restaurant::first();
    }

    public static function getCachedRestaurantDetails()
    {
        return Cache::rememberForever('restaurantDetails', function () {
            return self::refreshRestaurantDetails();
        });
    }

    public static function refreshAndCacheRestaurantDetails()
    {
        Cache::forget('restaurantDetails');
        self::getCachedRestaurantDetails(); // This fetches and caches the updated details
    }
}
