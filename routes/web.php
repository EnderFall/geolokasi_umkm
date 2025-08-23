<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OutletController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Search routes
Route::get('/search/outlets', [SearchController::class, 'searchOutlets'])->name('search.outlets');
Route::get('/search/menus', [SearchController::class, 'searchMenus'])->name('search.menus');
Route::get('/search/location', [SearchController::class, 'searchByLocation'])->name('search.location');

// Public outlet and menu routes
Route::get('/outlets', [OutletController::class, 'index'])->name('outlets.index');
Route::get('/outlets/{outlet}', [OutletController::class, 'show'])->name('outlets.show');
Route::get('/menus', [MenuController::class, 'index'])->name('menus.index');
Route::get('/menus/{menu}', [MenuController::class, 'show'])->name('menus.show');

// Protected routes
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Outlet management (penjual only)
    Route::middleware('role:penjual')->group(function () {
        Route::get('/outlets/create', [OutletController::class, 'create'])->name('outlets.create');
        Route::post('/outlets', [OutletController::class, 'store'])->name('outlets.store');
        Route::get('/outlets/{outlet}/edit', [OutletController::class, 'edit'])->name('outlets.edit');
        Route::put('/outlets/{outlet}', [OutletController::class, 'update'])->name('outlets.update');
        Route::delete('/outlets/{outlet}', [OutletController::class, 'destroy'])->name('outlets.destroy');
        Route::patch('/outlets/{outlet}/toggle-status', [OutletController::class, 'toggleStatus'])->name('outlets.toggle-status');
    });
    
    // Menu management (penjual only)
    Route::middleware('role:penjual')->group(function () {
        Route::get('/menus/create', [MenuController::class, 'create'])->name('menus.create');
        Route::post('/menus', [MenuController::class, 'store'])->name('menus.store');
        Route::get('/menus/{menu}/edit', [MenuController::class, 'edit'])->name('menus.edit');
        Route::put('/menus/{menu}', [MenuController::class, 'update'])->name('menus.update');
        Route::delete('/menus/{menu}', [MenuController::class, 'destroy'])->name('menus.destroy');
        Route::patch('/menus/{menu}/toggle-availability', [MenuController::class, 'toggleAvailability'])->name('menus.toggle-availability');
        Route::patch('/menus/{menu}/toggle-recommendation', [MenuController::class, 'toggleRecommendation'])->name('menus.toggle-recommendation');
    });
    
    // Rating and Review (authenticated users)
    Route::post('/outlets/{outlet}/rate', [RatingController::class, 'store'])->name('outlets.rate');
    Route::post('/menus/{menu}/review', [ReviewController::class, 'store'])->name('menus.review');
    
    // Orders (pembeli only)
    Route::middleware('role:pembeli')->group(function () {
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });
    
    // Order management (penjual only)
    Route::middleware('role:penjual')->group(function () {
        Route::get('/outlet/orders', [OrderController::class, 'outletOrders'])->name('outlet.orders');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });
});

// Review routes
Route::middleware(['auth'])->group(function () {
    Route::get('/reviews', [App\Http\Controllers\ReviewController::class, 'index'])->name('reviews.index');
    Route::post('/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'show'])->name('reviews.show');
    Route::get('/reviews/{review}/edit', [App\Http\Controllers\ReviewController::class, 'edit'])->name('reviews.edit');
    Route::put('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [App\Http\Controllers\ReviewController::class, 'destroy'])->name('reviews.destroy');
    
    // Review creation routes
    Route::get('/outlets/{outlet}/review', [App\Http\Controllers\ReviewController::class, 'createOutletReview'])->name('reviews.create.outlet');
    Route::get('/menus/{menu}/review', [App\Http\Controllers\ReviewController::class, 'createMenuReview'])->name('reviews.create.menu');
});

// Admin routes
Route::middleware(['auth', 'role:admin,superadmin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
    Route::put('/users/{user}', [AdminController::class, 'updateUser'])->name('users.update');
    Route::patch('/users/{user}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggle-status');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');
    
    Route::get('/outlets', [AdminController::class, 'outlets'])->name('outlets');
    Route::post('/outlets', [AdminController::class, 'storeOutlet'])->name('outlets.store');
    Route::put('/outlets/{outlet}', [AdminController::class, 'updateOutlet'])->name('outlets.update');
    Route::patch('/outlets/{outlet}/verify', [AdminController::class, 'verifyOutlet'])->name('outlets.verify');
    Route::patch('/outlets/{outlet}/unverify', [AdminController::class, 'unverifyOutlet'])->name('outlets.unverify');
    Route::delete('/outlets/{outlet}', [AdminController::class, 'destroyOutlet'])->name('outlets.destroy');
});
