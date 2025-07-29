<?php

namespace App\Http\Middleware;

use App\Http\Service\MenuService;
use App\Http\Service\RestaurantService;
use App\Http\Service\TableService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsurePosIsConfigured
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    // The list of routes that are EXEMPT from the check.
    protected $except = [
        'restaurant.show.config',
        'admin.tables.index',
        'admin.menus.index',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        // Each service method now uses its own internal caching logic.

        if ($this->shouldSkip($request)) {
            return $next($request);
        }

        $tableService = app(TableService::class);
        $menuService = app(MenuService::class);
        $isTableConfigured = $tableService->isTableConfigured();
        $isMenuConfigured = $menuService->isMenuConfigured();


        // Check if all configurations are set
        if (!$isTableConfigured) {
            Log::warning('Tables are not configured properly.');
            return redirect()->route('admin.tables.index')->with('danger', 'Please configure the tables first.');
        }
        if (!$isMenuConfigured) {
            Log::warning('Menu is not configured properly.');
            return redirect()->route('admin.menus.index')->with('danger', 'Please configure the menu first.');
        }

        // If all configurations are set, continue with the request.
        return $next($request);
    }

    protected function shouldSkip(Request $request): bool
    {
        return in_array($request->route()->getName(), $this->except);
    }
}
