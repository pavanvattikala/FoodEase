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
use GuzzleHttp\Middleware;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\RequestController;

Route::get('/', [WelcomeController::class, 'index']);
Route::get('/categories', [FrontendCategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [FrontendCategoryController::class, 'show'])->name('categories.show');
Route::get('/menus', [FrontendMenuController::class, 'index'])->name('menus.index');

Route::get('/reservation/step-one', [FrontendReservationController::class, 'stepOne'])->name('reservations.step.one');
Route::post('/reservation/step-one', [FrontendReservationController::class, 'storeStepOne'])->name('reservations.store.step.one');
Route::get('/reservation/step-two', [FrontendReservationController::class, 'stepTwo'])->name('reservations.step.two');
Route::post('/reservation/step-two', [FrontendReservationController::class, 'storeStepTwo'])->name('reservations.store.step.two');
Route::get('/thankyou', [WelcomeController::class, 'thankyou'])->name('thankyou');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');


Route::name('request.')->prefix('request')->group(function () {

    Route::get('/waiter', [RequestController::class, 'requestWaiter'])->name('waiter');
    Route::get('/bill', [RequestController::class, 'requestBill'])->name('bill');
    Route::get('/extra', [RequestController::class, 'requestExtra'])->name('extra');  

});

Route::middleware(['auth','waiter'])->name('waiter.')->prefix('waiter')->group(function () {

    //waiter home page 
    Route::get('/', [WaiterController::class, 'index'])->name('home'); 
    
    Route::get('/choosetable', [WaiterController::class, 'chooseTable'])->name('choose.table');

    Route::post('addtabletosession', [WaiterController::class, 'addTableToSesstion'])->name('table.add.toSession');

    Route::post('submitForBilling', [WaiterController::class, 'submitForBilling'])->name('table.submit.for.billing');


    //order step one
    Route::get('/order/step-one', [FrontendOrdersController::class, 'stepone'])->name('order.step.one');

    //current item to session
    Route::post('/order/addtocart', [FrontendOrdersController::class, 'addToCart'])->name('order.add.tocart');

    //remove current item to session
    Route::post('/order/removefromcart', [FrontendOrdersController::class, 'removefromcart'])->name('order.remove.fromcart');

    //show cart
    Route::get('/order/cart', [FrontendOrdersController::class, 'cart'])->name('order.cart');

    //clear cart
    Route::post('/order/clearcart', [FrontendOrdersController::class, 'clearcart'])->name('order.clear.cart');

    //order submit to kitchen
    Route::post('/order/submit', [FrontendOrdersController::class, 'submit'])->name('order.submit');

    //orders history
    Route::get('/orders/history', [FrontendOrdersController::class, 'orderHistory'])->name('orders.history');    

    Route::get('/orders/running', [FrontendOrdersController::class, 'runningOrders'])->name('orders.running');   
    
    Route::get('/orders/ready-for-pickup', [FrontendOrdersController::class, 'readyForPickUp'])->name('orders.ready.for.pickup');
    
    
    Route::post('/order/mark-as-served', [FrontendOrdersController::class, 'markAsServed'])->name('order.mark.as.served'); 

});


Route::middleware(['auth', 'kitchen'])->name('kitchen.')->prefix('kitchen')->group(function () {
    Route::get('/', [KitchenController::class, 'index'])->name('index');
    Route::post('/accept-order', [KitchenController::class, 'acceptOrder'])->name('accept.order');
    Route::post('/discard-order', [KitchenController::class, 'discardOrder'])->name('discard.order');

    Route::post('/complete-order', [KitchenController::class, 'completeOrder'])->name('complete.order');


    Route::post('/get-new-order-component',[KitchenController::class, 'getNewOrderComponent'])->name('get.new.order.component');
});


Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/menus', MenuController::class);
    Route::resource('/tables', TableController::class);
    Route::resource('/reservations', ReservationController::class);


    Route::get('/bills', [BillController::class, 'getBills'])->name('bills');

    Route::get('/bill/view/{id}', [BillController::class, 'viewBill'])->name('view.bill');

    Route::get('/bills/fd', [BillController::class, 'getBills'])->name('bills.update');


});

require __DIR__ . '/auth.php';
