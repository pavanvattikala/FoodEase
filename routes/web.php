<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\ReservationController;
use App\Http\Controllers\Admin\TableController;
use App\Http\Controllers\Frontend\CategoryController as FrontendCategoryController;
use App\Http\Controllers\Frontend\MenuController as FrontendMenuController;
use App\Http\Controllers\Frontend\ReservationController as FrontendReservationController;
use App\Http\Controllers\Frontend\OrdersController as FrontendOrdersController;
use App\Http\Controllers\WaiterController;
use App\Http\Controllers\Frontend\WelcomeController;
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
    
    //order step one
    Route::get('/order/step-one', [FrontendOrdersController::class, 'stepone'])->name('order.step.one');

    //route to add current item to session
    Route::post('/order/addtocart', [FrontendOrdersController::class, 'addToCart'])->name('order.add.tocart');

    //route to remove current item to session
    Route::post('/order/removefromcart', [FrontendOrdersController::class, 'removefromcart'])->name('order.remove.fromcart');

    // show cart
    Route::get('/order/cart', [FrontendOrdersController::class, 'cart'])->name('order.cart');

    // clear cart
    Route::post('/order/clearcart', [FrontendOrdersController::class, 'clearcart'])->name('order.clear.cart');

    Route::post('/order/clearcart', [FrontendOrdersController::class, 'clearcart'])->name('order.clear.cart');

    Route::post('/order/submit', [FrontendOrdersController::class, 'submit'])->name('order.submit');


    Route::get('/orders/step-two', [FrontendOrdersController::class, 'steptwo'])->name('order.step.two');    

    Route::get('/orders/history', [FrontendOrdersController::class, 'stepone'])->name('orders.history');    

    Route::get('/', [WaiterController::class, 'index'])->name('waiter.home');    

});


Route::middleware(['auth', 'admin'])->name('admin.')->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('/categories', CategoryController::class);
    Route::resource('/menus', MenuController::class);
    Route::resource('/tables', TableController::class);
    Route::resource('/reservations', ReservationController::class);
});

require __DIR__ . '/auth.php';
