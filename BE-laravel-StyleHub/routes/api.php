<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::middleware('auth:api')->group(function () {
    // USER PROFILE

    Route::get('/user/profile', [UserController::class, 'profileApi']);
    Route::put('/user/profile', [UserController::class, 'updateProfileAPI']);

    // CART - PRODUCT
    Route::get('/cart', [CartController::class, 'cart']);
    Route::post('/cart/add', [CartController::class, 'updateAddItem']);
    Route::delete('/cart/delete', [CartController::class, 'deleteItem']);

    // ORDERS
    Route::post('/order/add', [OrderController::class, 'add']);
    Route::get('/order', [OrderController::class, 'getAllOrderByUserId']);
    Route::get('/order/{id}', [OrderController::class, 'getOrder_Items']);

    // CHAT
    Route::get('/chat', [ChatController::class, 'getOrCreateChat']);
    Route::post('/chat/{id}/message', [ChatController::class, 'sendMessageFormUser']);
});
// PRODUCTS
Route::get('/products/latest', [ProductController::class, 'getLatest']);
Route::get('/products/search/suggestion', [ProductController::class, 'productSearchSuggestion']);
Route::get('/products/{id}', [ProductController::class, 'product_Detail']);
Route::get('/products', [ProductController::class, 'getAllProduct']);
Route::get('/size/suggest', [ProductController::class, 'sizeSuggest']);


// CATEGORIES
Route::get('/categories', [CategoryController::class, 'getAllCategories']);



// Authentication
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/confirm-token', [AuthController::class, 'confirmToken']);
Route::post('/reset-password', [AuthController::class, 'resetPassword']);
Route::post('/login-google', [AuthController::class, 'loginGoogle']);
