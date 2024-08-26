<?php

namespace App\Models;

use App\Enums\MenuType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use App\Models\Category;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'shortcode', 'price', 'description', 'image'];

    protected $casts = [
        'type' => MenuType::class,
    ];

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_menu');
    }

    protected static function refreshMenus()
    {
        return self::all();
    }

    public static function getCachedMenus()
    {
        return Cache::rememberForever('menus', function () {
            return self::refreshMenus();
        });
    }

    public static function refreshAndCacheMenus()
    {
        Cache::forget('menus');
        self::getCachedMenus();
    }

    public function save(array $options = [])
    {
        $saved = parent::save($options);
        self::refreshAndCacheMenus();
        Category::refreshAndCacheCategoriesWithMenus();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        self::refreshAndCacheMenus();
        Category::refreshAndCacheCategoriesWithMenus();
        return $deleted;
    }
}
