<?php

namespace App\Http\Controllers\POS;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Helpers\BillHelper;
use App\Helpers\RestaurantHelper;
use App\Helpers\TableHelper;
use App\Http\Controllers\Controller;
use App\Http\Service\MenuService;
use App\Http\Service\RestaurantService;
use App\Http\Service\TableService;
use App\Jobs\SaveAndPrintBill;
use App\Models\Bill;
use App\Models\Category;
use App\Models\Order;
use App\Models\Table;
use App\Models\TableLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PosController extends Controller
{
    private $menuService;
    private $tableService;
    private $restaurantService;

    public function __construct()
    {
        $this->menuService = new MenuService();
        $this->tableService = new TableService();
        $this->restaurantService = new RestaurantService();
    }
    public function index(Request $request)
    {
        $tableId = $request->tableId;

        $orderType = $tableId === 'takeaway' ? OrderType::Takeaway : OrderType::DineIn;

        // Get categories with menus, predefined notes, and payment types
        $categoriesWithMenus = $this->menuService->getCatergoriesWithMenus();
        $predefinedNotes = config('predefined_options.notes');
        $paymentTypes = json_decode($this->restaurantService->getRestaurantDetails()->payment_options);

        // Initialize variables
        $table = null;

        if ($orderType === OrderType::DineIn) {
            $table = Table::find($tableId);

            // Redirect if the table is not found
            if (!$table) {
                return redirect()->route('pos.tables');
            }
        }

        return view('pos.pos-index', compact(
            'categoriesWithMenus',
            'predefinedNotes',
            'paymentTypes',
            'table',
            'orderType'
        ));
    }


    public function tables()
    {
        $tables = $this->tableService->getTables();

        $takenTables = $tables->where('status', TableStatus::Unavaliable)
            ->map(function ($table) {
                return [
                    'id' => $table['id'],
                    'taken_at' => $table['taken_at'],
                ];
            })->values();

        $table_colors =  config('predefined_options.table_colors');

        $tableLocations = $this->tableService->getTableLocations();

        $paymentTypes = json_decode($this->restaurantService->getRestaurantDetails()->payment_options);

        return view('pos.tables', compact('tables', 'takenTables', 'table_colors', 'tableLocations', 'paymentTypes'));
    }

    public function billTable(Request $request)
    {
        $tableId = $request->tableId;
        $notes = $request->notes ? $request->notes : '';
        $paymentType = $request->paymentType ? $request->paymentType : 'cash';
        $discount = $request->discount ? $request->discount : 0;

        if ($request->has('printDuplicateBill')) {
            $billId = BillHelper::getLatestBillId($tableId);
            SaveAndPrintBill::dispatch($billId, $printDuplicateBill = true);

            return response()->json(['status' => 'success', 'message' => 'Duplicate bill printed']);
        }

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

    //tableOrders

    public function tableOrders($tableId)
    {

        $orders = Order::with('orderDetails')->with('orderDetails.menu')
            ->where('table_id', $tableId)
            ->where('status', '!=', OrderStatus::Closed)
            ->get();

        $table = Table::find($tableId);

        $billedOrders = Bill::where('table_id', $tableId)->where('created_at', '>=', $table->taken_at)->with('orders')->get();

        return view('pos.table-orders', compact('orders', 'table', 'billedOrders'));
    }
}
