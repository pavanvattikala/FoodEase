<?php

namespace App\Http\Controllers;

use App\Helpers\BillHelper;
use App\Helpers\RestaurantHelper;
use App\Models\Bill;
use App\Models\Restaurant;
use App\Providers\RestaurantServiceProvider;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class BillController extends Controller
{
    //

    // function getBills(){

    //     return view('admin.bills.index');
    // }

    function getBillsByDate(Request $request){
        
        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'))->addDay();

        //dd($endDate);

        $bills = Bill::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at','desc')->get();

        //dd($bills);

        $html='';

        foreach ($bills as $bill){

            $data = view('components.bill-component',compact('bill'))->render();

            $html=$html.$data;

        }

        return response()->json(["status"=>"success",'bills'=>$html]);

    }

    
    function viewBill($id){
        
        $bill = Bill::where('id',$id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();

        return view('admin.bills.edit',compact('bill'));
    }


    function printBill($id){
        
        $bill = Bill::where('id',$id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();


        $billId = $bill->bill_id;

        $billDetails = collect([
            'id' => $billId,
            'table_no' => $bill->table_id,
            'grand_total' => $bill->grand_total,
            'discount' => $bill->discount,
            "date"=>$bill->created_at->format('d M, Y h:i a')
        ]);
        
        $orderDetails = collect([]);
        
        foreach ($bill->orders as $order) {
            foreach ($order->orderDetails as $orderDetail) {
                $itemName = $orderDetail->menu->name;
                $quantity = $orderDetail->quantity;
                $price = $orderDetail->menu->price;


                // if key alredy exists it wil increment
                $orderDetails->put($itemName, ['quantity' => $quantity, 'price' => $price+5]);
               
            }
        }

        $restaurantDetails = RestaurantHelper::getCachedRestaurantDetails();


        $restaurant=[
            "name"=>$restaurantDetails->name,
            "address"=>$restaurantDetails->address,
            "phone"=>$restaurantDetails->phone,
            "tagline"=>$restaurantDetails->tagline,
        ];
        

        $fileName = 'bill_'.$billId . '.pdf';

        $html = view('admin.bills.print', compact('billDetails','orderDetails','restaurant'))->render();

        //option array

        $width=226.77; // 8cm
        $height=25;

        $customPaper = array( 0 , 0 , $width , $height );

        $pdf = pdf::loadHTML($html)->setPaper($customPaper);

        $pdf->render();

        $canvas = $pdf->getCanvas();

        // Get the page count of the rendered PDF
        $page_count = $canvas->get_page_number();

       // dd($page_count);


        unset( $pdf );

        //new pdf 

        $newHeight= ($height * $page_count);

        $dompdf = pdf::loadHTML($html);
        $dompdf->set_paper( array( 0 , 0 , $width , $newHeight ) );

        return $dompdf->stream($fileName);

        //return response()->download($directory, 'bill_' . $billId . '.pdf');
 
    }
}
