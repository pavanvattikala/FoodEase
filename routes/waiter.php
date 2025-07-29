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

    Route::get('/order/{table}', [WaiterController::class, 'orderScreen'])->name('order');

    Route::get('/choosetable', [WaiterController::class, 'chooseTable'])->name('tables.index');
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
