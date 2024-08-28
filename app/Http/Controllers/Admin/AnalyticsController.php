<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Service\ReportingService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    private $reportingService;

    public function __construct()
    {
        $this->reportingService = new ReportingService();
    }

    public function index()
    {
        return view('admin.analytics.index');
    }

    public function view(Request $request)
    {
        $report = $request->report;

        if ($report == 'sales-by-item') {
            return view('admin.analytics.salesByItem');
        }
    }

    public function salesByItemData(Request $request)
    {
        $data = $this->reportingService->salesByItemReport($request->start_date, $request->end_date)['data'];

        $html = '';

        foreach ($data as $index => $item) {

            $data = view('components.analytics.salesByItem', compact('item', 'index'))->render();
            $html .= $data;
        }

        return response()->json(["status" => "success", 'data' => $html, 'message' => 'Sales by item report generated successfully']);
    }
}
