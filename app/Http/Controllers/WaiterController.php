<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Models\Table;
use App\Models\Waiter;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Helpers\TableHelper;
use App\Models\Bill;
use App\Models\BillOrder;
use App\Models\Order;

class WaiterController extends Controller
{

    public function index(Request $request){
        return view('waiter.index');
    }

    public function chooseTable(Request $request){
        $tables = Table::all();

        $takenTables=$tables->where('status', TableStatus::Unavaliable)
                ->map(function ($table) {
                        return [
                            'id' => $table['id'],
                            'taken_at' => $table['taken_at'],
                        ];
                    })->values();

        return view('tables.select-table',compact('tables','takenTables'));
    }

    public function addTableToSesstion(Request $request){
        $tableId = $request->tableId;
        $reOrder = boolval($request->reOrder);
        

        if($reOrder === true){

            Session()->put("tableId",$tableId);
            Session()->put("reOrder",true);
        }
        else{
            Session()->put("tableId",$tableId);
        }

        return response()->json(['message' => 'true']);
    
    }

    public function submitForBilling(Request $request){

        $tableId = $request->tableId;

        $notes=null;
        $payment_method=null;

        $table = Table::find($tableId);
        $takenTime = $table->taken_at;

        // Get orders associated with the table since it was taken
        $orders = Order::where('table_id', $tableId)
                        ->where('created_at', '>=', $takenTime)
                        ->get();

    
        // Calculate total and grand total
        $total = $orders->sum('total');
        $discount = 0.0; // You can adjust this based on your logic
        $grandTotal = $total - $discount;
    
        // Create a new bill
        $bill = Bill::create([
            'table_id' => $tableId,
            'bill_amount' => $total,
            'discount' => $discount,
            'grand_total' => $grandTotal,
            'payment_method' => $payment_method, // Adjust as needed
            'notes' => $notes, // Adjust as needed
        ]);
    
        // Associate orders with the bill
        foreach ($orders as $order) {
            BillOrder::create([
                'bill_id' => $bill->id,
                'order_id' => $order->id,
            ]);
    
            // Update order status or any other fields if needed
            $order->update([
                'status' => 'closed', // Update with your desired order status
            ]);
        }
    
        // Update the table status or any other fields if needed
        $table->update([
            'status' => 'available', // Update with your desired table status
            'taken_at' => null,
        ]);
    
        // Optionally, you can return a response or redirect
        return response()->json(['status'=>'success','message' => 'Billing completed successfully']);
    }

    
}
