<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\InventoryController;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;


//------------------------------------------SA ADMIN INI------------------------------------------
// Authentication Routes (Login & Register)
Route::get('/login', function () {
    return view('Authentication.Login');
})->name('login');

Route::get('/register', function () {
    return view('Authentication.Register');
})->name('register');

// Landing Page
Route::get('/', function () {
    return view('landing');
});

// Order Page (Optional: You can remove this if not needed)
Route::get('/order', function () {
    return view('order');
})->name('order');

Auth::routes();

/*------------------------------------------
| Routes for Normal Users
-------------------------------------------*/
Route::middleware(['auth', 'user-access:user'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
});
/*------------------------------------------
| Routes for Admin
-------------------------------------------*/
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');
});
/*------------------------------------------
| Routes for Manager
-------------------------------------------*/
Route::middleware(['auth', 'user-access:manager'])->group(function () {
    Route::get('/manager/home', [HomeController::class, 'managerHome'])->name('manager.home');
});
/*------------------------------------------
| Routes for Dashboard
-------------------------------------------*/
Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('dashboard');
/*------------------------------------------
| Routes for inventory
-------------------------------------------*/
Route::get('/inventory', [HomeController::class, 'inventory'])->name('inventory');
/*------------------------------------------
| Routes for orders
-------------------------------------------*/
Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
/*------------------------------------------
| Routes for delivery
-------------------------------------------*/
Route::get('/delivery', [HomeController::class, 'delivery'])->name('delivery');

/*------------------------------------------
| Routes for promotions
-------------------------------------------*/
Route::get('/promotions', [HomeController::class, 'promotions'])->name('promotions');

// Inventory Routes
// GET request to show the inventory page (list of products)
Route::get('/inventory', [ProductController::class, 'index'])->name('inventory');

// POST request to store a new product
Route::post('/inventory', [ProductController::class, 'store'])->name('inventory.store');

// GET request to search for products (optional search functionality)
Route::get('/search-products', [ProductController::class, 'search'])->name('inventory.search');
// GET request to show a single product
Route::post('/add-product', [ProductController::class, 'store'])->name('products.store');

// CRUD Routes
Route::put('/products/{id}', [ProductController::class, 'update']);
Route::delete('/products/{id}', [ProductController::class, 'destroy']);
Route::delete('/products/{id}', [ProductController::class, 'create']);
Route::resource('products', ProductController::class);
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

//search ini
Route::get('/search-products', [ProductController::class, 'search'])->name('products.search');
Route::get('/search', [ProductController::class, 'searchProducts'])->name('products.search');

//------------------------------------------SA USER INI------------------------------------------
Route::get('/home', function () {
    return view('home');
})->name('home');
Route::get('/user_desktop', function () {
    return view('user_desktop');
})->name('user_desktop');

Route::get('/deliveryuser', function (): Factory|View {
    return view('deliveryuser');
})->name('deliveryuser');

Route::get('/logout', function () {
    return view('logout');
})->name('logout');

Route::get('/account_settings', function () {
    return view('account_settings');
})->name('account_settings');


//buy now
Route::get('/check-product/{id}', [ProductController::class, 'checkProduct']);
Route::get('/billing/{id}', [ProductController::class, 'billingPage']); // Example route for billing page
