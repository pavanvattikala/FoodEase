<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\RequestController;

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Waiter
 * -----------------------------------------------------------------------------------------------------------------------------
 */
Route::middleware(['auth', 'waiter'])->name('waiter.')->prefix('waiter')->group(function () {

    //waiter home page 
    Route::get('/', [WaiterController::class, 'index'])->name('index');

    Route::get('/choosetable', [WaiterController::class, 'chooseTable'])->name('choose.table');

    Route::post('addtabletosession', [WaiterController::class, 'addTableToSesstion'])->name('table.add.toSession');

    Route::post('submitForBilling', [WaiterController::class, 'submitForBilling'])->name('table.submit.for.billing');
});


/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Requsts
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::name('request.')->prefix('request')->group(function () {

    Route::get('/waiter', [RequestController::class, 'requestWaiter'])->name('waiter');
    Route::get('/bill', [RequestController::class, 'requestBill'])->name('bill');
    Route::get('/extra', [RequestController::class, 'requestExtra'])->name('extra');
});
