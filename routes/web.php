<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SellerController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use App\Http\Controllers\Seller\CanteenStatusController;
use App\Http\Controllers\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Student\CanteenController;
use App\Http\Controllers\Seller\HistoryController;
use App\Http\Controllers\Student\CartController;
use App\Http\Controllers\Student\CheckoutController;
use App\Http\Controllers\Student\DashboardController as StudentDashboardController;
use App\Http\Controllers\Student\OrderController as StudentOrderController;
use App\Http\Controllers\Student\ProductController as StudentProductController;
use App\Http\Controllers\Student\ProfileController as StudentProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Student\ActivationController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Login
Route::get('/login', [LoginController::class, 'create'])
    ->name('login');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware('throttle:10,1')
    ->name('login.store');

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Student Activation
Route::get('/student/activation', [ActivationController::class, 'create'])
    ->name('student.activation');

Route::post('/student/activation', [ActivationController::class, 'verify'])
    ->middleware('throttle:10,1')
    ->name('student.activation.verify');

Route::get('/student/activation/password', [ActivationController::class, 'password'])
    ->name('student.activation.password');

Route::post('/student/activation/password', [ActivationController::class, 'storePassword'])
    ->name('student.activation.password.store');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        // Students
        Route::resource('students', StudentController::class)
            ->except('show');

        // Generate activation link
        Route::post(
            'students/{student}/activation',
            [StudentController::class, 'activation']
        )->name('students.activation');

        // Reset password + generate new activation link
        Route::post(
            'students/{student}/reset-password',
            [StudentController::class, 'resetPassword']
        )->name('students.reset-password');

        // Sellers
        Route::resource('sellers', SellerController::class)
            ->except('show');

        // Categories
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');

        // Products
        Route::get('/products', [AdminProductController::class, 'index'])
            ->name('products.index');

        // Orders
        Route::get('/orders', [AdminOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])
            ->name('orders.show');

    });


/*
|--------------------------------------------------------------------------
| Seller Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:seller'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [SellerDashboardController::class, 'index'])
            ->name('dashboard');

        // Status kantin
        Route::get('/canteen/status', [CanteenStatusController::class, 'edit'])
            ->name('canteen.status');

        Route::put('/canteen/status', [CanteenStatusController::class, 'update'])
            ->name('canteen.status.update');

        // Products
        Route::resource('products', SellerProductController::class)->except('show');
        
        Route::put('/products/{product}/stock', [SellerProductController::class, 'updateStock'])
            ->name('products.stock');
        
        Route::post('/products/{product}/availability', [SellerProductController::class, 'toggleAvailability'])
            ->name('products.availability');
        
        // Orders
        Route::get('/orders', [SellerOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [SellerOrderController::class, 'show'])
            ->name('orders.show');

        Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus'])
            ->name('orders.status');

        // History
        Route::get('/history', [HistoryController::class, 'index'])
            ->name('history.index');

    });


/*
|--------------------------------------------------------------------------
| Student Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:student'])
    ->prefix('student')
    ->name('student.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [StudentDashboardController::class, 'index'])
            ->name('dashboard');

        // Profile
        Route::get('/profile', [StudentProfileController::class, 'show'])
            ->name('profile');

        Route::post('/topup', [StudentProfileController::class, 'topup'])
            ->name('topup');

        // Canteens
        Route::get('/canteens', [CanteenController::class, 'index'])
            ->name('canteens.index');

        Route::get('/canteens/{canteen}', [CanteenController::class, 'show'])
            ->name('canteens.show');

        // Products
        Route::get('/products/{product}', [StudentProductController::class, 'show'])
            ->name('products.show');

        // Cart
        Route::get('/cart', [CartController::class, 'index'])
            ->name('cart.index');

        Route::post('/cart/{product}', [CartController::class, 'store'])
            ->name('cart.store');

        Route::put('/cart/{product}', [CartController::class, 'update'])
            ->name('cart.update');

        Route::delete('/cart/{product}', [CartController::class, 'destroy'])
            ->name('cart.destroy');

        Route::delete('/cart', [CartController::class, 'clear'])
            ->name('cart.clear');

        // Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])
            ->name('checkout.index');

        Route::post('/checkout', [CheckoutController::class, 'store'])
            ->name('checkout.store');

        // Orders
        Route::get('/orders', [StudentOrderController::class, 'index'])
            ->name('orders.index');

        Route::get('/orders/{order}', [StudentOrderController::class, 'show'])
            ->name('orders.show');

        Route::get('/orders/{order}/status', [StudentOrderController::class, 'status'])
            ->name('orders.status');
    });


/*
|--------------------------------------------------------------------------
| Root Route
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->to(auth()->user()->homeRoute());
    }

    return redirect()->route('login');
});