<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Restaurant extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'tagline',
        'address',
        'phone',
        'email',
        'website',
        'waiter_module_enabled',
        'kitchen_module_enabled',
        'GST',
        'currency_symbol',
        'pending_order_sync_time',
        'waiter_sync_time',
        'minimum_delivery_time',
        'minimum_preparation_time',
        'order_live_view',
        'kot_live_view',
        'biller_printer',
        'kitchen_printer',
    ];

    protected static function refreshRestaurants()
    {
        return self::all();
    }

    public static function getCachedRestaurants()
    {
        return Cache::rememberForever('restaurants', function () {
            return self::refreshRestaurants();
        });
    }

    public static function refreshAndCacheRestaurants()
    {
        Cache::forget('restaurants');
        self::getCachedRestaurants();
    }

    public function save(array $options = [])
    {
        $saved = parent::save($options);
        self::refreshAndCacheRestaurants();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        self::refreshAndCacheRestaurants();
        return $deleted;
    }
}
