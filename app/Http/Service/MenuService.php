<?php

namespace App\Http\Service;

use App\Models\Category;

class MenuService extends Service
{
    private $menuCategoryCacheService;

    public function __construct(MenuCategoryCacheService $menuCategoryCacheService)
    {
        $this->menuCategoryCacheService = $menuCategoryCacheService;
    }

    public function getCatergoriesWithMenus()
    {
        return $this->menuCategoryCacheService->getCachedCategoriesWithMenus();
    }

    public function isMenuConfigured()
    {
        // Return true if the collection of menus is not empty
        return $this->menuCategoryCacheService->getCachedMenus()->isNotEmpty();
    }
}
