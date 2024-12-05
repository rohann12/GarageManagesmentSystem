<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthControllter;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\MobileOrderController;

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

Route::post('login',[LoginController::class,'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:api');

Route::get('orders', [MobileOrderController::class, 'index']);
Route::post('orders', [MobileOrderController::class, 'store']);
Route::get('orders/{id}', [MobileOrderController::class, 'show']);
Route::post('orders/{id}/complete', [MobileOrderController::class, 'complete']);
Route::post('orders/{orderId}/invoice', [MobileOrderController::class, 'createInvoice']);
Route::get('orders/completed', [MobileOrderController::class, 'completedIndex']);
Route::put('orders/{order}', [MobileOrderController::class, 'update']);
Route::delete('orders/{order}', [MobileOrderController::class, 'destroy']);