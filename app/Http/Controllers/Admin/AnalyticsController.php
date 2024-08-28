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
        $response = $this->reportingService->salesByItemReport($request->startDate, $request->endDate);

        return response()->json($response);
    }
}
