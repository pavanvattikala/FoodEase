<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use App\Models\EmployeeCategory;

class Admin
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
        $user = $request->user();



        if ($user->hasPermission(UserRole::Biller)) {

            $allowedRoutesForBiller = [
                'admin.bills.index',
                'admin.bill.destroy',
                'admin.bills.by.date',
                'admin.view.bill',
                'admin.stream.bill'
            ];

            if ($user->hasPermission(UserRole::Biller) && in_array($request->route()->getName(), $allowedRoutesForBiller)) {
                return $next($request);
            }
        }

        if ($user->hasPermission(UserRole::Admin)) {
            return $next($request);
        }

        // Redirect or respond as needed for non-waiter users
        abort(403, 'Unauthorized access.'); // You can customize the response as needed

    }
}
