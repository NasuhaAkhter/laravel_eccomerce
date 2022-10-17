<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrderController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
  
Route::prefix('ordermodule')->group(function () {  

Route::get('/getAllSingleOrders/{key}',  [OrderController::class, 'getAllSingleOrders']);
Route::get('/getAllOrders',  [OrderController::class, 'getAllOrders']);
Route::get('/getAllDrivers',  [OrderController::class, 'getAllDrivers']);
Route::get('/getOrderAddress/{id}',  [OrderController::class, 'getOrderAddress']);
Route::get('/getOrderDriver/{id}',  [OrderController::class, 'getOrderDriver']);
Route::post('/updateDeleveryAddress',  [OrderController::class, 'updateDeleveryAddress']);
Route::post('/order_limit_save',  [OrderController::class, 'order_limit_save']);
Route::get('/getOrderLimit',  [OrderController::class, 'getOrderLimit']);
Route::post('/getAvailableSchedule',  [OrderController::class, 'getAvailableSchedule']);
Route::post('/isAvailableSlot',  [OrderController::class, 'isAvailableSlot']);
Route::post('/checkAutoOrderSchedule',  [OrderController::class, 'checkAutoOrderSchedule']);
Route::post('/getShippingPrice',  [OrderController::class, 'getShippingPrice']);
Route::post('/updateProductPriceInOrder',  [OrderController::class, 'updateProductPriceInOrder']);

Route::post('/updateDriver',  [OrderController::class, 'updateDriver']);
Route::post('/storeOrder',  [OrderController::class, 'storeOrder']);
Route::post('/checkCoupon',  [OrderController::class, 'checkCoupon']);
Route::post('/storeSingleItem',  [OrderController::class, 'storeSingleItem']); 
Route::post('/deleteOrderSignleItem',  [OrderController::class, 'deleteOrderSignleItem']);
Route::post('/updateSingeleOrderItem',  [OrderController::class, 'updateSingeleOrderItem']);
Route::post('/updateOrderStatus',  [OrderController::class, 'updateOrderStatus']);
Route::post('/deliveredStatusUpdate',  [OrderController::class, 'deliveredStatusUpdate']);
Route::get('',  [OrderController::class, 'test']);

    // order images  
    Route::get('/getAllOrderImages/{id}',  [OrderController::class, 'getAllOrderImages']);
    Route::post('/deleteOrderImage',  [OrderController::class, 'deleteOrderImage']);
    Route::post('/storeOrderImages',  [OrderController::class, 'storeOrderImages']);
   
});