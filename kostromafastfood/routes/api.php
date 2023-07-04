<?php

use App\Http\Controllers\AuthApiManager;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductManager;
use App\Http\Controllers\OrderManager;
use App\Http\Controllers\DeliverManager;
use App\Http\Controllers\CustomerManager;

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

Route::any("/users/login", [AuthApiManager::class, "login"]);
Route::any("/users/register", [AuthApiManager::class, "registration"]);

Route::any("/users/delivery", [DeliverManager::class, "getDelivery"]);
Route::any("/users/delivery/success", [DeliverManager::class, "markStatusSuccess"]);
Route::any("/users/delivery/failed", [DeliverManager::class, "markStatusFailed"]);

Route::any("/product/list", [ProductManager::class, "getProducts"]);

Route::any("/users/cart/add", [OrderManager::class, "addToCart"]);
Route::any("/users/cart/remove", [OrderManager::class, "removeFromCart"]);
Route::any("/users/cart/list", [OrderManager::class, "getCart"]);
Route::any("/users/cart/confirm", [OrderManager::class, "confirmCart"]);
Route::any("/users/cart/clear", [OrderManager::class, "clearCart"]);
Route::any("/users/orders/list", [OrderManager::class, "getOrders"]);

Route::any("/users/address/update", [CustomerManager::class, "updateAddress"]);
