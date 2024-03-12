<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use Illuminate\Http\Request;

class RedirectController extends Controller
{
    //
    public function dashboard()
    {
        /** @var \App\User */
        $user = auth()->user();

        if ($user->hasPermission(UserRole::Admin)) {
            return redirect()->route('admin.index');
        } else if ($user->hasPermission(UserRole::Waiter)) {
            return redirect()->route('waiter.index');
        } else if ($user->hasPermission(UserRole::Biller)) {
            return redirect()->route('biller.index');
        } else if ($user->hasPermission(UserRole::Kitchen)) {
            return redirect()->route('kitchen.index');
        } else {
            return redirect()->route('login');
        }
    }
}
