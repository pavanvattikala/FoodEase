<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use Barryvdh\DomPDF\Facade\Pdf;
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


    function printBill($id){
        
        $bill = Bill::where('id',$id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();

        $billId =$bill->created_at->format('Ymd').$bill->id;

        $billDetails = collect([
            'id' => $billId,
            'table_no' => $bill->table_id,
            'grand_total' => $bill->grand_total,
            'discount' => $bill->discount,
        ]);
        
        $orderDetails = collect([]);
        
        foreach ($bill->orders as $order) {
            foreach ($order->orderDetails as $orderDetail) {
                $itemName = $orderDetail->menu->name;
                $quantity = $orderDetail->quantity;
                $price = $orderDetail->menu->price;
        
                if ($orderDetails->has($itemName)) {
                    $existingItem = $orderDetails->get($itemName);
                    $existingItem['quantity'] += $quantity;
                    $orderDetails->put($itemName, $existingItem);
                } else {
                    $orderDetails->put($itemName, ['quantity' => $quantity, 'price' => $price]);
                }
            }
        }

        

        $resName = "FoodEase";

        $address="aphb colony,idpl hyderabad 500054";

        $phone = "8341837776";

        $fileName = 'bill_'.$billId . '.pdf';

        $html = view('admin.bills.print', compact('billDetails','orderDetails','resName','address','phone'))->render();

        //option array

        $width=226.77;
        $height=20;

        $customPaper = array( 0 , 0 , $width , $height );

        $pdf = pdf::loadHTML($html)->setPaper($customPaper);

        $pdf->render();

        $canvas = $pdf->getCanvas();

        // Get the page count of the rendered PDF
        $page_count = $canvas->get_page_number();


        unset( $pdf );

        //new pdf 

        $newHeight= ($height * $page_count) + 10 ;

        $dompdf = pdf::loadHTML($html);
        $dompdf->set_paper( array( 0 , 0 , $width , $newHeight ) );

        return $dompdf->stream($fileName);

        //return response()->download($directory, 'bill_' . $billId . '.pdf');
 
    }
}
