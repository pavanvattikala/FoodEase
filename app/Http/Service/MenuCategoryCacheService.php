<?php

namespace App\Http\Service;

use Illuminate\Support\Facades\Cache;
use App\Models\Category;
use App\Models\Menu;

class MenuCategoryCacheService extends Service
{
    // -------------------------------------------------------------------------------------------------------

    // Refresh Functions

    private static function refreshCategories()
    {
        return Category::orderBy('rank')->get();
    }

    private static function refreshMenus()
    {
        return Menu::all();
    }

    private static function refreshCategoriesWithMenus()
    {
        return Category::with('menus')->orderBy('rank')->get();
    }

    // -------------------------------------------------------------------------------------------------------

    // Get Cached Data Functions

    public function getCachedCategoriesWithMenus()
    {
        return Cache::rememberForever('categoriesWithMenus', function () {
            return self::refreshCategoriesWithMenus();
        });
    }

    public function getCachedMenus()
    {
        return Cache::rememberForever('menus', function () {
            return self::refreshMenus();
        });
    }

    public function getCachedCategories()
    {
        return Cache::rememberForever('categories', function () {
            return self::refreshCategories();
        });
    }

    // -------------------------------------------------------------------------------------------------------

    // Cache Refresh Functions

    public static function refreshAndCacheCategoriesWithMenus()
    {
        Cache::forget('categoriesWithMenus');
        return self::refreshCategoriesWithMenus();
    }

    public static function refreshAndCacheMenus()
    {
        Cache::forget('menus');
        return self::refreshMenus();
    }

    public static function refreshAndCacheCategories()
    {
        Cache::forget('categories');
        return self::refreshCategories();
    }

    // Refresh all categories and menus
    public static function refreshMenusAndCategories()
    {
        self::refreshAndCacheCategories();
        self::refreshAndCacheMenus();
        self::refreshAndCacheCategoriesWithMenus();
    }
}
