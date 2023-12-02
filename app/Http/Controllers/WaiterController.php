<?php

namespace App\Http\Controllers;

use App\Models\Waiter;
use Illuminate\Http\Request;

class WaiterController extends Controller
{

    public function index(Request $request){
        return view('waiter.index');
    }

    
}
