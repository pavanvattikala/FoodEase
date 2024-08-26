<?php

namespace App\Http\Service;

use App\Models\Table;
use App\Models\TableLocation;

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
}
