<?php

namespace App\Http\Service;

use App\Models\Category;

class CategoryService extends Service
{
    private $menuCategoryCacheService;

    public function __construct(MenuCategoryCacheService $menuCategoryCacheService)
    {
        $this->menuCategoryCacheService = $menuCategoryCacheService;
    }

    public function getCatergories()
    {
        return $this->menuCategoryCacheService->getCachedCategories();
    }
}
