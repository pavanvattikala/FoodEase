<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Http\Service\MenuCategoryCacheService;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'description', 'rank'];

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'category_menu');
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
