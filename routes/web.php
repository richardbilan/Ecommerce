<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PromotionController;


//------------------------------------------ AUTHENTICATION ROUTES ------------------------------------------
Route::get('/login', fn() => view('Authentication.Login'))->name('login');
Route::get('/register', fn() => view('Authentication.Register'))->name('register');

//------------------------------------------ LANDING PAGE ------------------------------------------
Route::get('/', fn() => view('landing'))->name('landing');

//------------------------------------------ USER ROUTES ------------------------------------------
Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('/home', [ProductController::class, 'home'])->name('home');
    Route::get('/deliveryuser', fn(): Factory|View => view('deliveryuser'))->name('deliveryuser');
    Route::get('/account_settings', fn() => view('account_settings'))->name('account_settings');
});

//------------------------------------------ ADMIN ROUTES ------------------------------------------
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
});

//------------------------------------------ MANAGER ROUTES ------------------------------------------
Route::middleware(['auth', 'user-access:manager'])->group(function () {
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});

//------------------------------------------ DASHBOARD ROUTE ------------------------------------------
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');

//------------------------------------------ INVENTORY ROUTES ------------------------------------------
// Show the inventory page (static page or dashboard)
// INVENTORY ROUTE - Displays the inventory view with products
Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');

// CRUD operations for products within inventory
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/inventory/products', [InventoryController::class, 'index'])->name('inventory.products');
    Route::post('/inventory/products', [InventoryController::class, 'store'])->name('products.store');
    Route::put('/inventory/products/{id}', [InventoryController::class, 'update'])->name('products.update');
    Route::delete('/inventory/products/{id}', [InventoryController::class, 'destroy'])->name('products.destroy');
});

//------------------------------------------ OTHER ROUTES ------------------------------------------
Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
Route::get('/delivery', [HomeController::class, 'delivery'])->name('delivery');
Route::get('/promotions', [HomeController::class, 'promotions'])->name('promotions');

// Buy now / check product routes
Route::get('/check-product/{id}', [ProductController::class, 'checkProduct'])->name('product.check');
Route::get('/billing/{id}', [ProductController::class, 'billingPage'])->name('billing.page');

// Show single product (for user side)
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');

//------------------------------------------ LOGOUT ------------------------------------------
Route::get('/logout', fn() => view('landing'))->name('logout');

Auth::routes();
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');
});
//procduct shits
Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');
Route::get('/home', [ProductController::class, 'showProducts'])->name('home');
Route::get('/inventory/products/search', [ProductController::class, 'search'])->name('products.search');

// User homepage (show active promotions)
Route::get('/user/home', [PromotionController::class, 'index'])->name('user.home');

// Admin routes for promotions (CRUD)
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/promotions', [PromotionController::class, 'adminIndex'])->name('admin.promotions');
    Route::post('/admin/promotions', [PromotionController::class, 'store'])->name('admin.promotions.store');
    Route::get('/admin/promotions/{id}/edit', [PromotionController::class, 'edit'])->name('admin.promotions.edit');
    Route::put('/admin/promotions/{id}', [PromotionController::class, 'update'])->name('admin.promotions.update');
    Route::delete('/admin/promotions/{id}', [PromotionController::class, 'destroy'])->name('admin.promotions.destroy');
});


Route::get('/deliveryuser', function () {
    return view('deliveryuser');
})->name('deliveryuser');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');
