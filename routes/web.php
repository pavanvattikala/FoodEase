<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\MenuController as FrontendMenuController;
use App\Http\Controllers\Frontend\ReservationController as FrontendReservationController;
use App\Http\Controllers\Frontend\OrderController as FrontendOrdersController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\Frontend\WelcomeController;
use App\Http\Controllers\KitchenController;
use App\Http\Controllers\KOTController;
use App\Http\Controllers\OrderSyncController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\RestaurantController;
use App\Http\Controllers\PosController;


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
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

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

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Waiter
 * -----------------------------------------------------------------------------------------------------------------------------
 */
Route::middleware(['auth', 'waiter'])->name('waiter.')->prefix('waiter')->group(function () {

    //waiter home page 
    Route::get('/', [WaiterController::class, 'index'])->name('home');

    Route::get('/choosetable', [WaiterController::class, 'chooseTable'])->name('choose.table');

    Route::post('addtabletosession', [WaiterController::class, 'addTableToSesstion'])->name('table.add.toSession');

    Route::post('submitForBilling', [WaiterController::class, 'submitForBilling'])->name('table.submit.for.billing');
});

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
    Route::resource('/reservations', ReservationController::class);


    // Route::get('/bills', [BillController::class, 'getBills'])->name('bills');

    Route::get("/bills", function () {
        return view('admin.bills.index');
    })->name('bills');

    Route::get('/bills-by-date', [BillController::class, 'getBillsByDate'])->name('bills.by.date');


    Route::get('/bill/view/{id}', [BillController::class, 'viewBill'])->name('view.bill');

    Route::get('/bill/print/{id}', [BillController::class, 'StreamBillToBrowser'])->name('stream.bill');

    Route::get('/bills/fd', [BillController::class, 'getBills'])->name('bills.update');

    Route::get('/KOTs', [KOTController::class, 'displayKOTs'])->name('KOTs');
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


Route::middleware(['auth', 'admin'])->name('pos.')->prefix('pos')->group(function () {
    Route::redirect('/', '/pos/tables')->name('index');
    Route::get('/index', [PosController::class, 'index'])->name('main');
    Route::get('/tables', [PosController::class, 'tables'])->name('tables');
    Route::post('addtabletosession', [PosController::class, 'addTableToSesstion'])->name('table.add.toSession');
    Route::post('/table/submit-for-billing', [PosController::class, 'billTable'])->name('table.bill');
    Route::post('/table/settle', [PosController::class, 'settleTable'])->name('table.settle');
});

/**
 * -----------------------------------------------------------------------------------------------------------------------------
 * Routes for Order
 * -----------------------------------------------------------------------------------------------------------------------------
 */

Route::middleware(['auth'])->name('order.')->prefix('order')->group(function () {
    //order step one
    Route::get('/step-one', [FrontendOrdersController::class, 'stepone'])->name('step.one');

    //current item to session
    Route::post('/addtocart', [FrontendOrdersController::class, 'addToCart'])->name('add.tocart');

    //remove current item to session
    Route::post('/removefromcart', [FrontendOrdersController::class, 'removefromcart'])->name('remove.fromcart');

    //show cart
    Route::get('/cart', [FrontendOrdersController::class, 'cart'])->name('cart');

    //clear cart
    Route::post('/clearcart', [FrontendOrdersController::class, 'clearcart'])->name('clear.cart');

    //order submit to kitchen
    Route::post('/submit', [FrontendOrdersController::class, 'submit'])->name('submit');

    Route::get('/KOT-view', [FrontendOrdersController::class, 'KOTView'])->name('KOT.view');

    Route::post('/mark-as-served', [FrontendOrdersController::class, 'markAsServed'])->name('mark.as.served');
    Route::post('/mark-as-prepared', [FrontendOrdersController::class, 'markAsPrepared'])->name('mark.as.prepared');
    Route::post('/mark-as-closed', [FrontendOrdersController::class, 'markAsClosed'])->name('mark.as.closed');
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
