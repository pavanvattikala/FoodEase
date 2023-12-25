<?php

/**
 * File-level doc comment for RestaurantHelper.php
 *
 * PHP version 7.4.3
 *
 * @category Helpers
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */

namespace App\Helpers;

use App\Models\Restaurant;
use Illuminate\Support\Facades\Cache;

/**
 * Class RestaurantHelper
 *
 * @category Helpers
 * @package  App\Helpers
 * @author   Pavan Vattikala <pavanvattikala54@gmail.com>
 * @license  MIT License
 * @link     https://pavanvattikala.com/foodease/documentation
 */
class RestaurantHelper
{

    /**
     * Refresh the restaurant details.
     *
     * @return Restaurant
     */
    protected static function refreshRestaurantDetails()
    {
        return Restaurant::first();
    }

    /**
     * Get the cached restaurant details.
     *
     * @return Restaurant
     */
    public static function getCachedRestaurantDetails()
    {
        return Cache::rememberForever(
            'restaurantDetails',
            function () {
                return self::refreshRestaurantDetails();
            }
        );
    }

    /**
     * Refresh and cache the restaurant details.
     *
     * @return void
     */
    public static function refreshAndCacheRestaurantDetails()
    {
        Cache::forget('restaurantDetails');
        self::getCachedRestaurantDetails();
    }
}
