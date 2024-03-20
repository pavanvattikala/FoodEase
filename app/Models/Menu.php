<?php

namespace App\Models;

use App\Enums\MenuType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
