<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Enums\OrderType;
use App\Enums\TableStatus;
use App\Helpers\TableHelper;
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

        if (session()->has('tableData')) {
            $tableId = session()->get('tableData')['tableId'];
            $prevOrders = Order::with('orderDetails')->with('orderDetails.menu')
                ->where('table_id', $tableId)
                ->where('status', '!=', OrderStatus::Closed)
                ->get();
        }

        return view('pos.pos-index', compact('categoriesWithMenus', 'predefinedNotes', 'prevOrders'));
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
}
