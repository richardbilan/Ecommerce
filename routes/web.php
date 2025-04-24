<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\PromotionController;
use App\Http\Controllers\OrderController; 
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\PayMongoController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\GCashController;
use App\Http\Controllers\CafeLocationController;
use App\Http\Controllers\WebhookController; // added this line


Route::post('/pay-with-gcash', [PaymentController::class, 'payWithGCash'])->name('pay.gcash');
Route::get('/payment-success', function () {
    return 'Payment Successful!';
});
Route::get('/payment-failed', function () {
    return 'Payment Failed!';
});

use Illuminate\Http\Request;
use App\Models\Order;

use App\Http\Controllers\ProfileController;



//------------------------------------------ AUTHENTICATION ROUTES ------------------------------------------
Route::get('/login', fn() => view('Authentication.Login'))->name('login');
Route::get('/register', fn() => view('Authentication.Register'))->name('register');

//------------------------------------------ LANDING PAGE ------------------------------------------
Route::get('/', fn() => view('landing'))->name('landing');

//------------------------------------------ USER ROUTES ------------------------------------------

Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/admin/home', [HomeController::class, 'adminHome'])->name('admin.home');

    Route::get('/home', [ProductController::class, 'home'])->name('home');
    Route::get('/deliveryuser/{orderId}', [OrderController::class, 'showDeliveryUser'])->name('deliveryuser');
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
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
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
Route::get('/home', [ProductController::class, 'home'])->name('home');
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

// Order status update route for AJAX
Route::post('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

Route::get('/search', [ProductController::class, 'search'])->name('products.search');


//inventory routes
// Example for web.php (for a standard form submission)
Route::post('/products', [ProductController::class, 'store'])->name('products.store');

Route::get('/deliveryuser', [OrderController::class, 'deliveryUserIndex'])->name('deliveryuser');
// Route::view('/deliveryuser', 'deliveryuser')->name('deliveryuser');

//order routes

Route::get('/deliveryuser/{orderId}', [OrderController::class, 'showReceipt']);
Route::get('/deliveryuser/{orderId}', [OrderController::class, 'showDeliveryUser']);
Route::get('/deliveryuser/{orderId}', [OrderController::class, 'showDeliveryUser'])->name('deliveryuser');

//other routes
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

//promotions


Route::get('/promotions', [PromotionController::class, 'index'])->name('promotions');
Route::post('/promotions', [PromotionController::class, 'store'])->name('promotions.store');
Route::put('/promotions/{id}', [PromotionController::class, 'update'])->name('promotions.update');
Route::delete('/promotions/{id}', [PromotionController::class, 'destroy'])->name('promotions.destroy');
Route::get('/check-promo', [PromotionController::class, 'checkPromo']);



//profile
Route::post('/update-profile-image', [UserController::class, 'updateProfileImage'])->name('update.profile.image');
Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
Route::post('/update-profile-image', [UserController::class, 'updateProfileImage'])->name('user.updateProfileImage');
Route::post('/update-profile', [UserController::class, 'updateProfile'])->name('update.profile');
Route::post('/update-password', [UserController::class, 'updatePassword'])->name('update.password');


//favorites
Route::post('/favorite/{productId}', [FavoriteController::class, 'toggle'])->middleware('auth');

Route::post('/update-profile-image', [ProfileController::class, 'updateProfileImage'])->name('update.profile.image');

//location
Route::post('/update-location', [AccountController::class, 'updateLocation'])->name('update.location');
Route::post('/orders/{order}/review', [OrderController::class, 'storeReview'])->name('orders.review');
Route::get('/gcash-payment-return', [App\Http\Controllers\PaymentController::class, 'gcashPaymentReturn'])->name('gcash.payment.return');
Route::get('/gcash/payment-success', [App\Http\Controllers\PaymentController::class, 'gcashPaymentSuccess'])->name('gcash.payment.success');
Route::get('/gcash/payment-failed', [App\Http\Controllers\PaymentController::class, 'gcashPaymentFailed'])->name('gcash.payment.failed');

// Add these routes in the authenticated group
Route::middleware(['auth'])->group(function () {
    // Profile update routes
    Route::post('/profile/update', [AccountController::class, 'updateProfile'])->name('update.profile');
    Route::post('/profile/image/update', [AccountController::class, 'updateProfileImage'])->name('update.profile.image');
    Route::post('/profile/location/update', [AccountController::class, 'updateLocation'])->name('update.location');
});

// Location management routes
Route::middleware(['auth', 'user-access:admin'])->group(function () {
    Route::get('/location', [CafeLocationController::class, 'index'])->name('location');
});

// Order placement
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

// GCash/PayMongo payment link creation (AJAX)
Route::post('/paymongo/gcash', [PayMongoController::class, 'payWithGCash'])->name('paymongo.gcash');

// GCash redirect form handler
Route::post('/pay-gcash-link', [App\Http\Controllers\PaymentController::class, 'payWithGCashLink'])->name('pay.gcash.link');

// GCash/PayMongo payment status and callbacks (keep as needed)
Route::get('/gcash-payment-return', [App\Http\Controllers\PaymentController::class, 'gcashPaymentReturn'])->name('gcash.payment.return');
Route::get('/gcash/payment-success', [App\Http\Controllers\PaymentController::class, 'gcashPaymentSuccess'])->name('gcash.payment.success');
Route::get('/gcash/payment-failed', [App\Http\Controllers\PaymentController::class, 'gcashPaymentFailed'])->name('gcash.payment.failed');
Route::get('/gcash/callback', function () {
    return redirect('deliveryuser')->with('success', 'Payment completed!');
})->name('gcash.callback');

Route::post('webhook-receiver', [App\Http\Controllers\WebhookController::class, 'webhook'])->name('webhook');
