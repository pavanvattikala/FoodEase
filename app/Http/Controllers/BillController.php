<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Helpers\PDFHelper;
use App\Models\Bill;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $includeDeleted = $request->input('includeDeleted') === 'true';
        $onlyDeleted = $request->input('onlyDeleted') === 'true';

        /** @var \App\User */
        $user = auth()->user();

        $billsQuery = Bill::whereBetween('created_at', [$startDate, $endDate])->orderBy('created_at', 'desc');

        $totalSales = $billsQuery->get()->sum('grand_total');

        // include delted bills for admin
        if ($user->hasPermission(UserRole::Admin) && $includeDeleted) {
            $billsQuery->withTrashed();
        }

        // only deleted bills for admin 
        if ($user->hasPermission(UserRole::Admin) && $onlyDeleted) {
            $billsQuery->onlyTrashed();
        }

        $bills = $billsQuery->get();

        $html = '';

        foreach ($bills as $index => $bill) {
            $data = view('components.bill-component', compact('bill', 'index'))->render();
            $html .= $data;
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

    public function destroy($billid)
    {
        $bill = Bill::find($billid);
        $bill->delete();
        return redirect()->route('admin.bills.index')->with('success', 'Bill deleted successfully.');
    }
}
