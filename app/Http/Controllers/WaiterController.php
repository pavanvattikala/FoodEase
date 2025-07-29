<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Helpers\BillHelper;
use App\Helpers\TableHelper;
use App\Http\Service\MenuService;
use App\Models\Table;
use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\BillOrder;
use App\Models\Order;
use App\Models\TableLocation;
use Illuminate\Support\Facades\Session;
use App\Http\Service\TableService;
use App\Http\Service\RestaurantService;
use App\Enums\OrderType;

class WaiterController extends Controller
{
    private $tableService;
    private $restaurantService;
    private $menuService;

    public function __construct(TableService $tableService, RestaurantService $restaurantService, MenuService $menuService)
    {
        $this->tableService = $tableService;
        $this->restaurantService = $restaurantService;
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        return view('waiter.index');
    }

    public function chooseTable()
    {
        $tablesWithLocations = $this->tableService->getTablesWithOrderSums()->groupBy('location.name');

        $table_colors =  config('predefined_options.table_colors');

        $paymentTypes = json_decode($this->restaurantService->getRestaurantDetails()->payment_options);

        return view('tables.select-table', compact('tablesWithLocations', 'table_colors', 'paymentTypes'));
    }

    public function orderScreen(Request $request)
    {
        $tableId = $request->table;


        $orderType = OrderType::DineIn;

        // Get categories with menus, predefined notes, and payment types
        $categoriesWithMenus = $this->menuService->getCatergoriesWithMenus();
        $predefinedNotes = config('predefined_options.notes');
        $paymentTypes = json_decode($this->restaurantService->getRestaurantDetails()->payment_options);

        // Initialize variables

        $table = Table::find($tableId);

        // Redirect if the table is not found
        if (!$table) {
            return redirect()->route('pos.tables');
        }

        return view('waiter.order-screen', compact(
            'categoriesWithMenus',
            'predefinedNotes',
            'paymentTypes',
            'table',
            'orderType'
        ));
    }
}
