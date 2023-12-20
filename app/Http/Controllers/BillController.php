<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    //

    function displayBills(){
        $bills = Bill::all();

        return view('admin.bills.index',compact('bills'));
    }
}
