<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Restaurant Information
    |--------------------------------------------------------------------------
    */

    'name' => 'Ramana Restaurant',
    'tagline' => 'Home Food Healty Food',
    'address' => 'APHB Colony',
    'phone' => '8341837776',
    'email' => 'ramanarestaurents@gmail.com',
    'website' => 'https://www.ramanarestaurent.com',
    'takeout_enabled' => true,
    'delivery_enabled' => true,


    /*
    |--------------------------------------------------------------------------
    | Timing and Synchronization
    |
    | All times in seconds
    |--------------------------------------------------------------------------
    */

    'pending_order_sync_time' => 5, 
    'waiter_sync_time' => 5, 
    'minimum_delivery_time' => 1800, 
    'minimum_preparation_time' => 15, 

    /*
    |--------------------------------------------------------------------------
    | Live Views
    |--------------------------------------------------------------------------
    */

    'order_live_view' => 'desc', // 'asc' or 'desc'
    'kot_live_view' => 'asc', // 'asc' or 'desc'

    /*
    |--------------------------------------------------------------------------
    | Payment Options
    |--------------------------------------------------------------------------
    */

    'payment_options' => [
        'cash' => true,
        'credit_card' => true,
        'online_payment' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Social Media Links
    |--------------------------------------------------------------------------
    */

    'social_media' => [
        'facebook' => 'https://www.facebook.com/myrestaurant',
        'twitter' => 'https://twitter.com/myrestaurant',
        'instagram' => 'https://www.instagram.com/myrestaurant',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tax Configurations
    |--------------------------------------------------------------------------
    */

    'tax_rate' => 5, // Tax rate in percentage
    'currency_symbol' => '₹',

    /*
    |--------------------------------------------------------------------------
    | Reservation Configuration
    |--------------------------------------------------------------------------
    */

    'reservation_enabled' => true,
    'reservation_advance_notice' => 7200, // in seconds



];
