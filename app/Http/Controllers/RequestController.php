<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequestController extends Controller
{
    //

    public function requestWaiter(Request $request){
        //return waiter

        $message = "Booking Your Waiter";
        $minutes = 5;
        $seconds = 0;
    
        return view('waiter.booking', compact('message', 'minutes', 'seconds'));
    }

    public function requestBill(Request $request){
        //return bill
    }

    public function requestExtra(Request $request){
        //return extra
    }
}
