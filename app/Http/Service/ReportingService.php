<?php

namespace App\Http\Service;

use App\Helpers\DateHelper;
use App\Helpers\Printers\OrderItem;
use App\Models\Category;
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

    public function salesByCategoryReport($startDate, $endDate)
    {
        [$startDate, $endDate] = DateHelper::formatDatesForReport($startDate, $endDate);

        $catergories = Category::all();

        $data = [];

        foreach ($catergories as $category) {
            $categoryData = OrderDetail::join('menus', 'order_details.menu_id', '=', 'menus.id')
                ->join('category_menu', 'menus.id', '=', 'category_menu.menu_id')
                ->where('category_menu.category_id', $category->id)
                ->whereBetween('order_details.created_at', [$startDate, $endDate])
                ->groupBy('order_details.menu_id')
                ->selectRaw('SUM(order_details.quantity) as no_of_sales')
                ->selectRaw('SUM(order_details.quantity * menus.price) as total_amount')
                ->orderBy('no_of_sales', 'desc')
                ->get();


            // add the category name
            $data[$category->id]['category'] = $category->name;

            // add the total number of sales for the category
            $data[$category->id]['no_of_sales'] = $categoryData->sum('no_of_sales');

            // add the total sales for the category
            $data[$category->id]['total_amount'] = $categoryData->sum('total_amount');
        }

        // convert the data to an array
        $data = array_values($data);
        return $this->successResponse("success", "Sales by category report generated successfully", $data);
    }
}
