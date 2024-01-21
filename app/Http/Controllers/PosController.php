<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Helpers\BillHelper;
use App\Helpers\TableHelper;
use App\Jobs\SaveAndPrintBill;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Http\Request;

class PosController extends Controller
{
    //
    public function index()
    {
        $categoriesWithMenus = Category::with('menus')->get();

        $predefinedNotes = config('predefined_options.notes');

        $prevOrders = null;
        $isTableToBePaid = false;

        if (session()->has('tableData')) {
            $tableId = session()->get('tableData')['tableId'];
            $prevOrders = Order::with('orderDetails')->with('orderDetails.menu')
                ->where('table_id', $tableId)
                ->where('status', '!=', OrderStatus::Closed)
                ->get();

            $isTableToBePaid = Table::where('id', $tableId)->where('status', TableStatus::Printed)->exists();
        }

        return view('pos.pos-index', compact('categoriesWithMenus', 'predefinedNotes', 'prevOrders', 'isTableToBePaid'));
    }

    public function tables()
    {
        $tables = Table::all();
        $takenTables = $tables->where('status', TableStatus::Unavaliable)
            ->map(function ($table) {
                return [
                    'id' => $table['id'],
                    'taken_at' => $table['taken_at'],
                ];
            })->values();

        $table_colors =  config('predefined_options.table_colors');

        return view('pos.tables', compact('tables', 'takenTables', 'table_colors'));
    }
    public function addTableToSesstion(Request $request)
    {
        $tableId = $request->tableId;
        session()->forget('orderType');
        session()->forget('tableData');

        if ($tableId == -1) {
            session()->put("orderType", OrderType::Takeaway->value);
        } else {
            TableHelper::addTableToSession($request->tableId);
            session()->put("orderType", OrderType::DineIn->value);
        }

        return response()->json(['message' => 'true']);
    }

    public function billTable(Request $request)
    {
        $tableId = $request->tableId;
        $notes = $request->notes ? $request->notes : '';
        $paymentType = $request->paymentType ? $request->paymentType : 'cash';
        $discount = $request->discount ? $request->discount : 0;

        $billId = BillHelper::createTableBill($tableId, $request->notes, $paymentType, $discount);

        SaveAndPrintBill::dispatch($billId);

        return response()->json(['status' => 'success', 'billId' => $billId]);
    }

    public function settleTable(Request $request)
    {
        TableHelper::markTableAsPaid($request->tableId);

        $tableId = $request->tableId;

        $table = Table::find($tableId);

        $orders = $table->orders()->where('status', '!=', OrderStatus::Closed)->get();

        foreach ($orders as $order) {
            $order->status = OrderStatus::Closed;
            $order->save();
        }

        $lastBill = Bill::where('table_id', $request->tableId)->latest()->first();

        $lastBill->payment_method = $request->paymentType;

        $lastBill->save();


        return response()->json(['status' => 'success']);
    }
}
