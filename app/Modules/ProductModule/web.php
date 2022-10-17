<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
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
Route::prefix('productmodule')->group(function () { 
Route::get('',  [ProductController::class, 'test']);
// Requested Products
    Route::get('/getRequestedProducts',  [ProductController::class, 'getRequestedProducts']);
    Route::post('/deleteRequestedProduct',  [ProductController::class, 'deleteRequestedProduct']);

// product routes
    Route::get('/getAllProduct',  [ProductController::class, 'getAllProduct']);
    Route::get('/getProductById',  [ProductController::class, 'getProductById']);
    Route::get('/productDetails/{id}',  [ProductController::class, 'productDetails']);
    Route::get('/getAllProductWithVariationPrice',  [ProductController::class, 'getAllProductWithVariationPrice']);
    Route::get('/getAllProductByCategoryWithVariationPrice',  [ProductController::class, 'getAllProductByCategoryWithVariationPrice']);
    Route::post('/storeProduct',  [ProductController::class, 'storeProduct']);
    Route::post('/updateProduct',  [ProductController::class, 'updateProduct']);
    Route::post('/deleteProduct',  [ProductController::class, 'deleteProduct']);
    // product images routes
    Route::get('/getAllProductImages/{id}',  [ProductController::class, 'getAllProductImages']);
    Route::post('/deleteProductImage',  [ProductController::class, 'deleteProductImage']);
    Route::post('/storeProductImages',  [ProductController::class, 'storeProductImages']);
    // Product Band
    Route::post('/storeProductBand',  [ProductController::class, 'storeProductBand']);
    Route::post('/updateProductBand',  [ProductController::class, 'updateProductBand']);
    Route::post('/deleteProductBand',  [ProductController::class, 'deleteProductBand']);
    Route::get('/getAllProductBand',  [ProductController::class, 'getAllProductBand']);
    Route::get('/getAllBrand',  [ProductController::class, 'getAllBrand']);
    // category
    Route::get('/getAllCategoryById/{id}',  [ProductController::class, 'getAllCategoryById']);
    Route::post('/storeCategory',  [ProductController::class, 'storeCategory']);
    Route::post('/updateCategory',  [ProductController::class, 'updateCategory']);
    Route::post('/deleteCategory',  [ProductController::class, 'deleteCategory']);
    Route::get('/getAllCategory',  [ProductController::class, 'getAllCategory']);
    // Product  category   
    Route::get('/getAllProductCategoryById/{id}',  [ProductController::class, 'getAllProductCategoryById']);
    Route::post('/storeProductCategory',  [ProductController::class, 'storeProductCategory']);
    Route::post('/updateProductCategory',  [ProductController::class, 'updateProductCategory']);
    Route::post('/deleteProductCategory',  [ProductController::class, 'deleteProductCategory']);
    Route::get('/getAllProductCategory',  [ProductController::class, 'getAllProductCategory']);
    // Product Sub Category
    Route::get('/getAllProductSubCategoryById/{id}',  [ProductController::class, 'getAllProductSubCategoryById']);
    Route::post('/storeProductSubCategory',  [ProductController::class, 'storeProductSubCategory']);
    Route::post('/updateProductSubCategory',  [ProductController::class, 'updateProductSubCategory']);
    Route::post('/deleteProductSubCategory',  [ProductController::class, 'deleteProductSubCategory']);
    Route::get('/getAllProductSubCategory',  [ProductController::class, 'getAllProductSubCategory']);
    // Restaurant Category 
    Route::get('/getAllRestaurantCategory/{id}',  [ProductController::class, 'getAllRestaurantCategory']);
    Route::get('/getRestaurantMenuCategory/{id}',  [ProductController::class, 'getRestaurantMenuCategory']);
    // Cupon
    Route::get('/getAllCupon',  [ProductController::class, 'getAllCupon']);
    Route::post('/storeCupon',  [ProductController::class, 'storeCupon']);
    Route::post('/updateCupon',  [ProductController::class, 'updateCupon']);
    Route::post('/deleteCupon',  [ProductController::class, 'deleteCupon']);
    // Menu Category
    Route::post('/updateRestaurentCategory',  [ProductController::class, 'updateRestaurentCategory']);
    Route::post('/storeRestaurentCategory',  [ProductController::class, 'storeRestaurentCategory']);
    Route::post('/deleteRestaurentCategory',  [ProductController::class, 'deleteRestaurentCategory']);
    // Route::get('/getAllMenuCategory',  [ProductController::class, 'getAllMenuCategory']);
    // Discount announcement
    Route::post('/updateDiscountAnnouncement',  [ProductController::class, 'updateDiscountAnnouncement']);
    Route::get('/getAllDiscountAnnouncement',  [ProductController::class, 'getAllDiscountAnnouncement']);

    Route::post('/updateProductColumn',  [ProductController::class, 'updateProductColumn']);

    // Product Variation
    Route::get('/getAllProductVariation',  [ProductController::class, 'getAllProductVariation']);
    Route::post('/storeProductVarition',  [ProductController::class, 'storeProductVarition']);
    Route::post('/updateProductVariation',  [ProductController::class, 'updateProductVariation']);
    Route::post('/deleteProductVariation',  [ProductController::class, 'deleteProductVariation']);

    // Product Tags
    Route::get('/getTagsByCategory/{franchise_id}/{category_id}',  [ProductController::class, 'getTagsByCategory']);


});