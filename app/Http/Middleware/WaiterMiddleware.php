<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use App\Models\EmployeeCategory;
use SebastianBergmann\Environment\Console;

class WaiterMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user is a waiter

        $user = $request->user();

        if ($user->hasPermission(UserRole::Waiter)) {
            return $next($request);
        }

        // Redirect or respond as needed for non-waiter users
        abort(403, 'Unauthorized access.'); // You can customize the response as needed
    }
}
