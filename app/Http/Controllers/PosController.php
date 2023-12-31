<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Table;
use Illuminate\Http\Request;

class PosController extends Controller
{
    //
    public function index()
    {
        $categoriesWithMenus = Category::with('menus')->get();

        $predefinedNotes = config('predefined_options.options');

        return view('pos.pos-index', compact('categoriesWithMenus', 'predefinedNotes'));
    }

    public function saveOrder(Request $request)
    {
        dd($request->all());
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
        return view('pos.tables', compact('tables', 'takenTables'));
    }
    public function addTableToSesstion(Request $request)
    {
        $tableId = $request->tableId;
        $reOrder = boolval($request->reOrder);

        Session()->put("tableId", $tableId);

        return response()->json(['message' => 'true']);
    }
}
