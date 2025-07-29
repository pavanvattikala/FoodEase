<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use App\Models\EmployeeCategory;
use SebastianBergmann\Environment\Console;
use App\Http\Service\RestaurantService;

class WaiterMiddleware
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

        // Check if the restaurant has the waiter module enabled
        $isWaiterConfigured = $this->restaurantService->isWaiterEnabled();

        if (!$isWaiterConfigured) {
            abort(501, 'Waiter module is not enabled.');
        }

        // Check if the authenticated user is a waiter

        $user = $request->user();

        $userHasWaiterPermission = $user->hasPermission(UserRole::Waiter);

        if (!$userHasWaiterPermission) {
            abort(403, 'Unauthorized access to waiter module.');
        }

        if ($userHasWaiterPermission) {
            return $next($request);
        }

        // Redirect or respond as needed for non-waiter users
        abort(403, 'Unauthorized access.');
    }
}
