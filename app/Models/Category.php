<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Menu;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'description'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'category_menu');
    }

    protected static function refreshCategoriesWithMenus()
    {
        return self::with('menus')->orderBy('rank')->get();
    }

    public static function getCachedCategoriesWithMenus()
    {
        return Cache::rememberForever('categoriesWithMenus', function () {
            return self::refreshCategoriesWithMenus();
        });
    }

    public static function refreshAndCacheCategoriesWithMenus()
    {
        Cache::forget('categoriesWithMenus');
        self::getCachedCategoriesWithMenus();
    }

    public function save(array $options = [])
    {
        $saved = parent::save($options);
        self::refreshAndCacheCategoriesWithMenus();
        Menu::refreshAndCacheMenus();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        self::refreshAndCacheCategoriesWithMenus();
        Menu::refreshAndCacheMenus();
        return $deleted;
    }
}
