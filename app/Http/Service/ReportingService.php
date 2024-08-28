<?php

namespace App\Http\Service;

use App\Helpers\DateHelper;
use App\Models\OrderDetail;
use Carbon\Carbon;

class ReportingService extends Service
{
    public function salesByItemReport($startDate, $endDate)
    {
        [$startDate, $endDate] = DateHelper::formatDatesForReport($startDate, $endDate);


        $data = OrderDetail::select('menus.name as menu', 'menus.price as price')
            ->join('menus', 'order_details.menu_id', '=', 'menus.id')
            ->whereBetween('order_details.created_at', [$startDate, $endDate])
            ->groupBy('order_details.menu_id')
            ->selectRaw('SUM(order_details.quantity) as no_of_sales')
            ->orderBy('no_of_sales', 'desc')
            ->get();

        return $this->successResponse("success", "Sales by item report generated successfully", $data);
    }
}
