<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'shortcode', 'price', 'description', 'image'];

    public function category()
    {
        return $this->belongsToMany(Category::class, 'category_menu');
    }
}
