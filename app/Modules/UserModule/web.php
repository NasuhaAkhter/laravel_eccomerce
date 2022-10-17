<?php

use Illuminate\Support\Facades\Route;
// use App\Http\Controllers\UserController;
use App\Http\Controllers\UserController;



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
 
Route::post('/usermodule/login', [UserController::class, 'login']); 
 
Route::prefix('usermodule')->group( function () {
    Route::get('',  [UserController::class, 'test']);  
    Route::get('/logout', [UserController::class, 'logout']);
    Route::post('/lightModeSave', [UserController::class, 'lightModeSave']);
    Route::post('/darkModeSave', [UserController::class, 'darkModeSave']);
    Route::get('/getAllUsers', [UserController::class, 'getAllUsers']); 
    Route::get('/getAllCoustomers', [UserController::class, 'getAllCoustomers']);
    Route::get('/getFranchiseId', [UserController::class, 'getFranchiseId']);
    Route::get('/getNewFranchiseOwner', [UserController::class, 'getNewFranchiseOwner']);
    Route::get('/getAllFranchiseOwner', [UserController::class, 'getAllFranchiseOwner']);
    Route::get('/getAllCountry', [UserController::class, 'getAllCountry']);
    Route::get('/getAllDriver', [UserController::class, 'getAllDriver']);
    Route::post('/updateUser', [UserController::class, 'updateUser']);
    Route::post('/deleteUser', [UserController::class, 'deleteUser']);
    Route::post('/createUser', [UserController::class, 'createUser']); 
    Route::post('/upload/images', [UserController::class, 'uploadImages']);

    Route::get('/getAllShopOwner', [UserController::class, 'getAllShopOwner']); 
    Route::post('/createShopOwner', [UserController::class, 'createShopOwner']);
    Route::post('/updateShopOwner', [UserController::class, 'updateShopOwner']);

    Route::post('/createDriver', [UserController::class, 'createDriver']);
    Route::post('/updateDriver', [UserController::class, 'updateDriver']);
 
    // galary Route
    Route::get('/getGalary', [UserController::class, 'getGalary']);
    Route::post('/createGalary', [UserController::class, 'createGalary']);
    Route::post('/updateGalary', [UserController::class, 'updateGalary']);
    Route::post('/deleteGalary', [UserController::class, 'deleteGalary']);

    // Banner Route
    Route::get('/getAllBanners', [UserController::class, 'getAllBanners']);
    Route::post('/createBanner', [UserController::class, 'createBanner']);
    Route::post('/updateBanner', [UserController::class, 'updateBanner']);
    Route::post('/deleteBanner', [UserController::class, 'deleteBanner']);

    // UserBalance
    Route::get('/getAlluserBalance', [UserController::class, 'getAlluserBalance']);
    Route::post('/createUserBalance', [UserController::class, 'createUserBalance']);
    Route::post('/updateUserBalance', [UserController::class, 'updateUserBalance']);
    Route::post('/deleteUserBalance', [UserController::class, 'deleteUserBalance']);
    // terms and conditions
    Route::get('/getAllrules', [UserController::class, 'getAllrules']);
    Route::post('/updateRules', [UserController::class, 'updateRules']);

    // On Board Screens
    Route::get('/getAllScreens', [UserController::class, 'getAllScreens']);
    Route::post('/updateOnBoardScreen', [UserController::class, 'updateOnBoardScreen']);
    // Promotion Screens
    Route::get('/getPromotionScreens', [UserController::class, 'getPromotionScreens']);
    Route::post('/updatePromotionStatus', [UserController::class, 'updatePromotionStatus']);
 
    // PlanRequest
    Route::get('/getAllPlanRequest', [UserController::class, 'getAllPlanRequest']);
    Route::post('/updatePlanRequestStatus', [UserController::class, 'updatePlanRequestStatus']);
    Route::post('/updatePlanRequest', [UserController::class, 'updatePlanRequest']);
    Route::post('/deletePlanRequest', [UserController::class, 'deletePlanRequest']);
    // Transaction
    Route::get('/getAllTransaction/{id}', [UserController::class, 'getAllTransaction']);
    Route::post('/createUserBalance', [UserController::class, 'createUserBalance']);
    Route::post('/updateUserBalance', [UserController::class, 'updateUserBalance']);
    Route::post('/updateTransaction', [UserController::class, 'updateTransaction']);
    // Delivery Address
    Route::get('/getDeliveryAddress/{id}', [UserController::class, 'getDeliveryAddress']);
    Route::get('/getSingleAddress', [UserController::class, 'getSingleAddress']);
    Route::post('/addDeliveryAddress', [UserController::class, 'addDeliveryAddress']);
    Route::post('/updateDeliveryAddress', [UserController::class, 'updateDeliveryAddress']);
    Route::post('/deleteAddress', [UserController::class, 'deleteAddress']);
    // profile
    Route::post('/profile_pic_update', [UserController::class, 'profile_pic_update']);
    Route::post('/password_update', [UserController::class, 'password_update']);
    Route::post('/info_update', [UserController::class, 'info_update']);
    Route::post('/insertPoints', [UserController::class, 'insertPoints']);


    // userType
    Route::get('/getUsertype', [UserController::class, 'getUsertype']);
    // dashboard data
    
    Route::get('/getRecentOrderTable', [UserController::class, 'getRecentOrderTable']);
    Route::get('/getDriverTable', [UserController::class, 'getDriverTable']);
    Route::get('/getCustomerTable', [UserController::class, 'getCustomerTable']);
    Route::get('/getShopTable', [UserController::class, 'getShopTable']);
    Route::get('/getFranchiseTable', [UserController::class, 'getFranchiseTable']);
    Route::get('/getProductCount', [UserController::class, 'getProductCount']);
    Route::get('/getFranchiseCount', [UserController::class, 'getFranchiseCount']);
    Route::get('/getOrderCount', [UserController::class, 'getOrderCount']);
    Route::get('/get_total_pending_order', [UserController::class, 'get_total_pending_order']);
    Route::get('/getCustomerCount', [UserController::class, 'getCustomerCount']);
    Route::get('/getShopCount', [UserController::class, 'getShopCount']);
    Route::get('/getdeliveredOrderCount', [UserController::class, 'getdeliveredOrderCount']);
    Route::get('/getMemberRequestCount', [UserController::class, 'getMemberRequestCount']);
    Route::get('/getdriverCount', [UserController::class, 'getdriverCount']);
    // Route::get('/getTodaysExpense', [UserController::class, 'getTodaysExpense']);
    // Route::get('/getTodaysSale', [UserController::class, 'getTodaysSale']);
    // Route::get('/getTodaysCashback', [UserController::class, 'getTodaysCashback']);
    Route::get('/getTodaysProfit', [UserController::class, 'getTodaysProfit']);
    Route::get('/getTotalAmmount', [UserController::class, 'getTotalAmmount']);
    Route::get('/getUsertype', [UserController::class, 'getUsertype']);
    Route::get('/getUserInfo/{id}', [UserController::class, 'getUserInfo']);

    // ******************************************* Expense ********************************************************************

    // Expense Category
    Route::get('/getExpenseCategory', [UserController::class, 'getExpenseCategory']);
    Route::post('/addExpenseCategory', [UserController::class, 'addExpenseCategory']);
    Route::post('/updateExpenseCategory', [UserController::class, 'updateExpenseCategory']);
    Route::post('/deleteExpenseCategory', [UserController::class, 'deleteExpenseCategory']);

    // Expense Category
    Route::get('/getExpenseSheets', [UserController::class, 'getExpenseSheets']);
    Route::post('/addExpenseSheets', [UserController::class, 'addExpenseSheets']);
    Route::post('/updateExpenseSheets', [UserController::class, 'updateExpenseSheets']);
    Route::post('/deleteExpenseSheets', [UserController::class, 'deleteExpenseSheets']);
    // Package List
    Route::get('/getAllPackage', [UserController::class, 'getAllPackage']);
    Route::post('/addPackage', [UserController::class, 'addPackage']);
    Route::post('/updatePackage', [UserController::class, 'updatePackage']);
    Route::post('/deletePackage', [UserController::class, 'deletePackage']);

    // Statistics
    Route::get('/getStatistics', [UserController::class, 'getStatistics']);

    //Manuall Push Notification
    Route::post('/sendPushNotificationToCustmer', [UserController::class, 'sendPushNotificationToCustmer']);


    
});

