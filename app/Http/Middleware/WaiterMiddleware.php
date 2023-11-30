<?php

namespace App\Http\Middleware;

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
        $waiterCategory = EmployeeCategory::where('name',"waiter")->first()->id;

        $adminCategory = EmployeeCategory::where('name',"admin")->first()->id;


        if ($request->user() && ( $request->user()->category_id == $waiterCategory || $request->user()->category_id == $adminCategory) ) {
            return $next($request);
        }

        // Redirect or respond as needed for non-waiter users
        abort(403, 'Unauthorized access.'); // You can customize the response as needed
    }
}
