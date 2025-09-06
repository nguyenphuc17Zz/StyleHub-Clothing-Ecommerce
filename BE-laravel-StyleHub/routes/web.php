<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductVariantController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

// AUTH
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'loginAdmin']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'registerAdmin']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name(name: 'dashboard');
    Route::get('/', [DashboardController::class, 'index'])->name('home');
    Route::get('/users', [UserController::class, 'data']);

    //  CATEGORIES
    Route::get('categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories/create', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{id}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{id}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('categories/{id}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    //PRODUCTS
    Route::get('products', [ProductController::class, 'index'])->name('products.index');
    Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('products/store', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::delete('products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

    // PRODUCT VARIANT
    Route::get(uri: 'variants', action: [ProductVariantController::class, 'index'])->name('variants.index');
    Route::get(uri: 'variants/create', action: [ProductVariantController::class, 'create'])->name('variants.create');
    Route::post(uri: 'variants', action: [ProductVariantController::class, 'store'])->name('variants.store');
    Route::get('/variants/{id}/edit', [ProductVariantController::class, 'edit'])->name('variants.edit');
    Route::put('/variants/{id}/update', [ProductVariantController::class, 'update'])->name('variants.update');
    Route::delete('variants/{id}', [ProductVariantController::class, 'destroy'])->name('variants.destroy');

    // IMAGES
    Route::get(uri: 'images', action: [ImageController::class, 'index'])->name('images.index');
    Route::get(uri: 'images/create', action: [ImageController::class, 'create'])->name('images.create');
    Route::post(uri: 'images', action: [ImageController::class, 'store'])->name('images.store');
    Route::get('/images/{id}/edit', [ImageController::class, 'edit'])->name('images.edit');
    Route::put('/images/{id}/update', [ImageController::class, 'update'])->name('images.update');
    Route::delete('images/{id}', [ImageController::class, 'destroy'])->name('images.destroy');

    // USERS
    Route::get(uri: 'users', action: [UserController::class, 'index'])->name('users.index');
    Route::get(uri: 'users/create', action: [UserController::class, 'create'])->name('users.create');
    Route::post(uri: 'users', action: [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{id}/update', [UserController::class, 'update'])->name('users.update');
    Route::delete('users/{id}', [UserController::class, 'destroy'])->name('users.destroy');

    // ORDER
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::put('/orders/{id}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('/orders/{id}', [OrderController::class, 'destroy'])->name('orders.destroy');

    // CHATS
    Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::get('/chats/{id}', [ChatController::class, 'show'])->name('chats.show');
    Route::post('/chats/{id}', [ChatController::class, 'sendMessageFromAdmin'])->name('chats.send');

   
});
