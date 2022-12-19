<?php

use App\Http\Controllers\Bundle\Api\Admin\CustomerManagementController;
use App\Http\Controllers\Bundle\Api\Admin\DealerController;
use App\Http\Controllers\Bundle\Api\Admin\UserController;
use App\Http\Controllers\Bundle\Api\Product\CategoryController;
use App\Http\Controllers\Bundle\Api\Product\DebtController;
use App\Http\Controllers\Bundle\Api\Product\OrderController;
use App\Http\Controllers\Bundle\Api\Product\ProductController;
use App\Http\Controllers\Bundle\Auth\AdminAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('/login', [AdminAuthController::class, 'login']);
    Route::post('/register', [AdminAuthController::class, 'register']);
    Route::post('/logout', [AdminAuthController::class, 'logout'])->middleware('auth:api');
    Route::post('/refresh', [AdminAuthController::class, 'refresh'])->middleware('auth:api');
    Route::get('/user-profile', [AdminAuthController::class, 'userProfile'])->middleware('api');
    Route::post('/change-pass', [AdminAuthController::class, 'changePassWord']);
});

Route::group([
    'prefix' => 'admin'
], function() {
    Route::post('/create-user', [UserController::class, 'createUser'])->middleware('auth:api');
    Route::get('/list-user', [UserController::class, 'getUsers'])->middleware('auth:api');
    Route::get('/user-detail/{id}', [UserController::class, 'getUser'])->middleware('auth:api');
    Route::put('/update-user/{id}', [UserController::class, 'updateUser'])->middleware('auth:api');
    Route::delete('/delete-user/{id}', [UserController::class, 'deleteUser'])->middleware('auth:api');

    Route::post('/create-customer', [CustomerManagementController::class, 'createCustomer'])->middleware('auth:api');
    Route::get('/list-customer', [CustomerManagementController::class, 'getCustomers'])->middleware('auth:api');
    Route::get('/customer-detail/{id}', [CustomerManagementController::class, 'getCustomer'])->middleware('auth:api');
    Route::put('/update-customer/{id}', [CustomerManagementController::class, 'updateCustomer'])->middleware('auth:api');
    Route::delete('/delete-customer/{id}', [CustomerManagementController::class, 'deleteCustomer'])->middleware('auth:api');

    Route::post('/create-product', [ProductController::class, 'createProduct'])->middleware('auth:api');
    Route::get('/list-product', [ProductController::class, 'getProducts'])->middleware('auth:api');
    Route::get('/product-detail/{id}', [ProductController::class, 'getProduct'])->middleware('auth:api');
    Route::put('/update-product/{id}', [ProductController::class, 'updateProduct'])->middleware('auth:api');

    Route::post('/create-category', [CategoryController::class, 'createCategory'])->middleware('auth:api');
    Route::get('/list-category', [CategoryController::class, 'getCategories'])->middleware('auth:api');
    Route::get('/category-detail/{id}', [CategoryController::class, 'getCategory'])->middleware('auth:api');
    Route::put('/update-category/{id}', [CategoryController::class, 'updateCategory'])->middleware('auth:api');

    Route::get('/product-attributes', [ProductController::class, 'getProductAttributes'])->middleware('auth:api');
    Route::get('/measure-unit', [ProductController::class, 'getMeasureUnit'])->middleware('auth:api');

    Route::post('/product-attribute-value', [ProductController::class, 'createAttributeValueForProduct'])->middleware('auth:api');
    Route::get('/product-attribute-values', [ProductController::class, 'getProductAttributeValues'])->middleware('auth:api');
    Route::get('/product-attribute-prices', [ProductController::class, 'getProductAttributePrices'])->middleware('auth:api');

    Route::get('/order/list-order', [OrderController::class, 'getOrders'])->middleware('auth:api');
    Route::post('/order/create-order', [OrderController::class, 'createOrder'])->middleware('auth:api');
    Route::get('/order/detail-order/{id}', [OrderController::class, 'getOrder'])->middleware('auth:api');
    Route::put('/order/payment-status', [OrderController::class, 'updatePaymentStatus'])->middleware('auth:api');
    Route::put('/order/delivery-status', [OrderController::class, 'updateDeliveryStatus'])->middleware('auth:api');

    Route::post('/import-good/import-good', [OrderController::class, 'createImportGood'])->middleware('auth:api');
    Route::delete('/import-good/restore-import-good/{id}', [OrderController::class, 'restoreImportGood'])->middleware('auth:api');
    Route::get('/import-good/import-goods', [OrderController::class, 'getImportGoods'])->middleware('auth:api');
    Route::get('/import-good/detail-import-good/{id}', [OrderController::class, 'getImportGood'])->middleware('auth:api');

    Route::post('/order/export-order', [OrderController::class, 'exportOrder'])->middleware('auth:api');

    Route::get('/dealer/dealers', [DealerController::class, 'getDealers'])->middleware('auth:api');

    Route::post('/debt/create-payment', [DebtController::class, 'createPayment'])->middleware('auth:api');
    Route::get('/debt/list-debt', [DebtController::class, 'getDebts'])->middleware('auth:api');
    Route::get('/debt/list-customer-debt/{id}', [DebtController::class, 'getCustomerDebtDetail'])->middleware('auth:api');
});
