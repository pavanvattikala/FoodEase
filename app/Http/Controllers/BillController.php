<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Illuminate\Http\Request;

class BillController extends Controller
{
    //

    function getBills(){

        $bills = Bill::all();

        return view('admin.bills.index',compact('bills'));
    }

    
    function viewBill($id){
        
        $bill = Bill::where('id',$id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();

        return view('admin.bills.edit',compact('bill'));
    }
}
