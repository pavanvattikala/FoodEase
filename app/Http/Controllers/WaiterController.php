<?php

namespace App\Http\Controllers;

use App\Models\Waiter;
use Illuminate\Http\Request;

class WaiterController extends Controller
{
    //

    public function requestWaiter(Request $request)
    {
        // requesting new waiter

        // send request or assign to new waiter dynamically based on system laod

        $message = "Booking Your Waiter";
        $minutes = 5;
        $seconds = 0;
    
        return view('waiter.booking', compact('message', 'minutes', 'seconds'));
    }

    
}
