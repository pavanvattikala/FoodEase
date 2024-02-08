<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RedirectController extends Controller
{
    //
    public function dashboard()
    {
        /** @var \App\User */
        $user = auth()->user();

        if ($user->hasPermission(1)) {
            //admin
            return redirect()->route('admin.index');
        } else if ($user->hasPermission(2)) {
            return redirect()->route('waiter.index');
        }
    }
}
