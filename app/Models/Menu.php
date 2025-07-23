<?php

namespace App\Models;

use App\Enums\MenuType;
use App\Http\Service\MenuCategoryCacheService;
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

    public function save(array $options = [])
    {
        $saved = parent::save($options);
        MenuCategoryCacheService::refreshMenusAndCategories();
        return $saved;
    }

    public function delete()
    {
        $deleted = parent::delete();
        MenuCategoryCacheService::refreshMenusAndCategories();
        return $deleted;
    }
}
