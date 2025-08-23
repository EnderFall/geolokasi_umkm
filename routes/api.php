<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MenuController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Public API routes
Route::get('/menus', [MenuController::class, 'index']);
Route::get('/outlets', [App\Http\Controllers\Api\OutletController::class, 'index']);
Route::get('/categories', [App\Http\Controllers\Api\CategoryController::class, 'index']);

// Protected API routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/orders', [App\Http\Controllers\Api\OrderController::class, 'store']);
    Route::get('/orders', [App\Http\Controllers\Api\OrderController::class, 'index']);
    Route::get('/orders/{order}', [App\Http\Controllers\Api\OrderController::class, 'show']);
    Route::patch('/orders/{order}/confirm', [App\Http\Controllers\Api\OrderController::class, 'confirm']);
    Route::patch('/orders/{order}/mark-ready', [App\Http\Controllers\Api\OrderController::class, 'markReady']);
    Route::patch('/orders/{order}/mark-delivered', [App\Http\Controllers\Api\OrderController::class, 'markDelivered']);
    Route::delete('/orders/{order}', [App\Http\Controllers\Api\OrderController::class, 'destroy']);
});
