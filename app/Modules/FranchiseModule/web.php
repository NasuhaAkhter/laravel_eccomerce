<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FranchiseController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
|    
*/

Route::prefix('franchisemodule')->group(function () {
     Route::get('/shippingPrice',  [FranchiseController::class, 'shippingPrice']);
     Route::post('/addShippingPrice',  [FranchiseController::class, 'addShippingPrice']);
     Route::post('/editShippingPrice',  [FranchiseController::class, 'editShippingPrice']);
     Route::get('/getSingleFranchise',  [FranchiseController::class, 'getSingleFranchise']);
     Route::get('/getMyFranchise',  [FranchiseController::class, 'getMyFranchise']);
    Route::get('/franchises',  [FranchiseController::class, 'getFranchiesList']);
    Route::get('/allfranchises',  [FranchiseController::class, 'getAllFranchiesList']);
    Route::get('/getAlldailySchedule',  [FranchiseController::class, 'getAlldailySchedule']);

    Route::post('/createNewFranchises',  [FranchiseController::class, 'createNewFranchises']);
    Route::post('/storeSchedule',  [FranchiseController::class, 'storeSchedule']);
    Route::post('/updateFranchises',  [FranchiseController::class, 'updateFranchises']);
    Route::post('/updateMyFranchises',  [FranchiseController::class, 'updateMyFranchises']);
    Route::post('/updateSchedule',  [FranchiseController::class, 'updateSchedule']);
    Route::post('/deleteFranchises',  [FranchiseController::class, 'deleteFranchises']);
    Route::post('/deleteSchedule',  [FranchiseController::class, 'deleteSchedule']);
    // gallery
    Route::post('/AddProductImage',  [FranchiseController::class, 'AddProductImage']);
    Route::post('/getAllGallery',  [FranchiseController::class, 'getAllGallery']);
    Route::post('/postGallery',  [FranchiseController::class, 'postGallery']);

    // shops
    Route::get('/getshopsById/{id}',  [FranchiseController::class, 'getshopsById']);
    Route::get('/getshops',  [FranchiseController::class, 'getshops']); 
    Route::get('/getAllPertnerShops',  [FranchiseController::class, 'getAllPertnerShops']);
    Route::get('/getAllPertnerShopsbyCategory/{id}',  [FranchiseController::class, 'getAllPertnerShopsbyCategory']);
    Route::get('/formatedgetApiData',  [FranchiseController::class, 'formatedgetApiDataChekck']);
    
    Route::post('/createNewshop',  [FranchiseController::class, 'createNewshop']);
    Route::post('/updateshop',  [FranchiseController::class, 'updateshop']);
    Route::post('/updateshopStatus',  [FranchiseController::class, 'updateshopStatus']);
    Route::post('/deleteshop',  [FranchiseController::class, 'deleteshop']);
     // dashboard
     Route::get('/getOrderCount',  [FranchiseController::class, 'getOrderCount']);
     Route::get('/statusWiseOrderCount',  [FranchiseController::class, 'statusWiseOrderCount']);
});

