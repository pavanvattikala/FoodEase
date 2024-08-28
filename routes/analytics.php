<?php

use App\Http\Controllers\Admin\AnalyticsController;
use Illuminate\Support\Facades\Route;

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Analytics
 * -----------------------------------------------------------------------------------------------------------------------------
 */
Route::middleware(['auth', 'biller'])->name('reporting.')->prefix('reporting')->group(function () {

    //Analytics Home Page

    Route::get('/', [AnalyticsController::class, 'index'])->name('index');

    //Sales By Item
    Route::get('/view/{report}', [AnalyticsController::class, 'view'])->name('view');

    //Sales By Item Data
    Route::get('/sales-by-item-data', [AnalyticsController::class, 'salesByItemData'])->name('salesByItemData');
});
