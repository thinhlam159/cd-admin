<?php

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
