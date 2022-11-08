<?php

use App\Http\Controllers\Bundle\Api\Admin\CustomerManagementController;
use App\Http\Controllers\Bundle\Api\Admin\UserController;
use App\Http\Controllers\Bundle\Api\Product\CategoryController;
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
});
