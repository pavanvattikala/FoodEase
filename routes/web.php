<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Admin\TableLocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Billing\BillController;
use App\Http\Controllers\BillerController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\MenuController as FrontendMenuController;
use App\Http\Controllers\Frontend\ReservationController as FrontendReservationController;
use App\Http\Controllers\Order\OrderController as OrderController;
use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\Kitchen\KOTController;
use App\Http\Controllers\Order\OrderSyncController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Restaurant\RestaurantController;
use App\Http\Controllers\POS\PosController;
use App\Http\Controllers\RedirectController;

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for FrontEnd & Reservation
 * -----------------------------------------------------------------------------------------------------------------------------
 */
Route::get('/', [WelcomeController::class, 'index']);
Route::get('/categories', [FrontendCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [FrontendCategoryController::class, 'show'])->name('categories.show');
Route::get('/menus', [FrontendMenuController::class, 'index'])->name('menus.index');

Route::get('/reservation/step-one', [FrontendReservationController::class, 'stepOne'])->name('reservations.step.one');
Route::post('/reservation/step-one', [FrontendReservationController::class, 'storeStepOne'])->name('reservations.store.step.one');
Route::get('/reservation/step-two', [FrontendReservationController::class, 'stepTwo'])->name('reservations.step.two');
Route::post('/reservation/step-two', [FrontendReservationController::class, 'storeStepTwo'])->name('reservations.store.step.two');
Route::get('/thankyou', [WelcomeController::class, 'thankyou'])->name('thankyou');

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Dashboard
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth'])->get('/dashboard', [RedirectController::class, 'dashboard'])->name('dashboard');


/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Admin
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/menus', MenuController::class);
    Route::resource('/tables', TableController::class);
    Route::resource('/table-location', TableLocationController::class);
    Route::resource('/reservations', ReservationController::class);
    Route::resource('/users', UserController::class);

    Route::get("/bills", function () {
        return view('admin.bills.index');
    })->name('bills.index');

    Route::delete('/bill/{id}', [BillController::class, 'destroy'])->name('bill.destroy');


    Route::get('/bills-by-date', [BillController::class, 'getBillsByDate'])->name('bills.by.date');


    Route::get('/bill/view/{id}', [BillController::class, 'viewBill'])->name('view.bill');

    Route::get('/bill/print/{id}', [BillController::class, 'StreamBillToBrowser'])->name('stream.bill');

    Route::get('/bills/fd', [BillController::class, 'getBills'])->name('bills.update');

    Route::get('/KOTs', [KOTController::class, 'displayKOTs'])->name('KOTs');
});

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Biller
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth', 'biller'])->name('biller.')->prefix('biller')->group(function () {
    Route::get('/', [BillerController::class, 'index'])->name('index');
});

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Restaurant
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth', 'admin'])->name('restaurant.')->prefix('restaurant')->group(function () {

    Route::get('/restaurant-config', [RestaurantController::class, 'showConfig'])->name('show.config');

    Route::post('/update-config', [RestaurantController::class, 'updateConfig'])->name('update.config');
});
/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for POS
 * -----------------------------------------------------------------------------------------------------------------------------
 */


Route::middleware(['auth', 'biller'])->name('pos.')->prefix('pos')->group(function () {
    Route::get('/select-table', [PosController::class, 'selectTable'])->name('tables');
    Route::get('/order', [PosController::class, 'index'])->name('main');
    Route::post('/table/submit-for-billing', [PosController::class, 'billTable'])->name('table.bill');
    Route::post('/table/settle', [PosController::class, 'settleTable'])->name('table.settle');
    Route::get('/table/orders/{tableId}', [PosController::class, 'tableOrders'])->name('table.orders');
});

/**
 * -------------------------------------------------s----------------------------------------------------------------------------
 * Routes for Order
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth'])->name('order.')->prefix('order')->group(function () {

    Route::post('/submit', [OrderController::class, 'submit'])->name('submit');

    Route::get('/KOT-view', [OrderController::class, 'KOTView'])->name('KOT.view');

    Route::post('/mark-as-served', [OrderController::class, 'markAsServed'])->name('mark.as.served');
    Route::post('/mark-as-prepared', [OrderController::class, 'markAsPrepared'])->name('mark.as.prepared');
    Route::post('/mark-as-closed', [OrderController::class, 'markAsClosed'])->name('mark.as.closed');
});

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Sync
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth', 'order'])->name('sync.')->prefix('sync')->group(function () {

    Route::get('/check-pending-orders-updates', [OrderSyncController::class, 'syncPendingOrder'])->name('pending.orders');
    Route::get('/check-pickup-orders-updates', [OrderSyncController::class, 'syncPickUpOrder'])->name('pickup.orders');
});

require __DIR__ . '/auth.php';
