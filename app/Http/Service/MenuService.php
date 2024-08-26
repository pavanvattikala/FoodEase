<?php

namespace App\Http\Service;

use App\Models\Category;

class MenuService extends Service
{
    public function getCatergoriesWithMenus()
    {
        return Category::getCachedCategoriesWithMenus();
    }
}
