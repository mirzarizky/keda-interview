<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\MessageController;
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

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', LoginController::class);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', LogoutController::class);
    });

    Route::middleware('user_type:Staff')->group(function () {
        Route::apiResource('customer', CustomerController::class)->only(['index', 'destroy']);
        Route::get('all-messages', [MessageController::class, 'getAllMessages']);
    });

    Route::middleware('user_type:Staff,Customer')->group(function () {
        Route::get('messages', [MessageController::class, 'index']);
        Route::post('message', [MessageController::class, 'sendMessage']);
    });
});
