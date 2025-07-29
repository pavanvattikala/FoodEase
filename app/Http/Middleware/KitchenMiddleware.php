<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use App\Models\EmployeeCategory;
use Closure;
use Illuminate\Http\Request;
use App\Http\Service\RestaurantService;

class KitchenMiddleware
{
    protected $restaurantService;

    public function __construct(RestaurantService $restaurantService)
    {
        $this->restaurantService = $restaurantService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        $user = $request->user();


        // Check if the authenticated user is a waiter
        $isKitchenConfigured = $this->restaurantService->isKitchenEnabled();

        if (!$isKitchenConfigured) {
            abort(501, 'Kitchen module is not enabled.');
        }

        $userHasKitchenPermission = $user->hasPermission(UserRole::Kitchen) || $user->hasPermission(UserRole::Biller);

        if (!$userHasKitchenPermission) {
            abort(403, 'Unauthorized access to kitchen module.');
        }

        if ($userHasKitchenPermission) {
            return $next($request);
        }

        // Redirect or respond as needed for non-waiter users
        abort(403, 'Unauthorized access.'); // You can customize the response as needed
    }
}
