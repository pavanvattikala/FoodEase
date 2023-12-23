<?php

namespace App\Providers;

use App\Models\Restaurant;
use Illuminate\Support\ServiceProvider;

class RestaurantServiceProvider extends ServiceProvider
{
    protected $restaurantDetails;

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        $this->refreshRestaurantDetails();

        // Share the restaurant details with all views
        view()->share('restaurantDetails', $this->restaurantDetails);
    }

    protected function refreshRestaurantDetails()
    {
        $this->restaurantDetails = Restaurant::first();
    }
}
