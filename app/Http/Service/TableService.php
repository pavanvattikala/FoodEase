<?php

namespace App\Http\Service;

use App\Models\Table;
use App\Models\TableLocation;
use Illuminate\Support\Facades\DB;
use App\Enums\OrderStatus;

class TableService extends Service
{
    public function getTables()
    {
        return Table::getCachedTables();
    }

    public function getTableLocations()
    {
        return TableLocation::getCachedTableLocations();
    }
    public function getOrderSumForTable($tableId)
    {
        return DB::table('orders')
            ->where('table_id', $tableId)
            ->where('status', '!=', OrderStatus::Closed)
            ->sum('total');
    }

    public function getTablesWithOrderSums()
    {
        $tables = $this->getTables();

        foreach ($tables as $table) {
            if ($table->taken_at) {
                $table->order_sum = $this->getOrderSumForTable($table->id);
            } else {
                $table->order_sum = null;
            }
        }

        return $tables;
    }
}
