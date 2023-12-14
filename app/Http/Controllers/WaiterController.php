<?php

namespace App\Http\Controllers;

use App\Enums\TableStatus;
use App\Models\Table;
use App\Models\Waiter;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use App\Helpers\TableHelper;

class WaiterController extends Controller
{

    public function index(Request $request){
        return view('waiter.index');
    }

    public function chooseTable(Request $request){
        $tables = Table::all();
        return view('tables.select-table',compact('tables'));
    }

    public function addTableToSesstion(Request $request){
        $tableId = $request->tableId;

        $isTableAvailable = TableHelper::checkIfTableAvailable($tableId);

        if($isTableAvailable){
            
            Session()->put("tableId",$tableId);

            return response()->json(['message' => 'true']);
        }
        else{
            return response()->json(['message' => 'false']);
        }
        
    }

    
}
