<?php

namespace App\Http\Controllers;

use App\Helpers\PDFHelper;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    //

    // function getBills(){

    //     return view('admin.bills.index');
    // }

    function getBillsByDate(Request $request)
    {

        $startDate = Carbon::parse($request->input('startDate'));
        $endDate = Carbon::parse($request->input('endDate'))->addDay();

        //dd($endDate);

        $bills = Bill::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc')->get();

        $totalSales = $bills->sum('grand_total');

        //dd($bills);

        $html = '';

        foreach ($bills as $bill) {

            $data = view('components.bill-component', compact('bill'))->render();

            $html = $html . $data;
        }

        return response()->json(["status" => "success", 'bills' => $html, 'totalSales' => $totalSales]);
    }


    function viewBill($id)
    {

        $bill = Bill::where('id', $id)->with('orders')->with('orders.orderDetails')->with('orders.orderDetails.menu')->first();

        return view('admin.bills.edit', compact('bill'));
    }


    function StreamBillToBrowser($id)
    {
        $billId = Bill::where('id', $id)->first()->bill_id;
        $fileName = 'bill_' . $billId . '.pdf';
        $filePath = 'bills/' . $fileName;

        if (!Storage::exists($filePath)) {
            PDFHelper::saveBillToDisk($id);
        }

        $fileContent = Storage::get($filePath);

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($filePath) . '"',
        ];

        // Stream the file to the browser
        return response($fileContent, 200, $headers);
    }
}
