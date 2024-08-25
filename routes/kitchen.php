<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Kitchen\KitchenController;

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Kitchen
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth', 'kitchen'])->name('kitchen.')->prefix('kitchen')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('index');
    Route::post('/accept-order', [KitchenController::class, 'acceptOrder'])->name('accept.order');
    Route::post('/discard-order', [KitchenController::class, 'discardOrder'])->name('discard.order');

    Route::post('/complete-order', [KitchenController::class, 'completeOrder'])->name('complete.order');

    Route::post('/get-new-order-component', [KitchenController::class, 'getNewOrderComponent'])->name('get.new.order.component');
});
