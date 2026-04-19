<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\BuyerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SellerDashboardController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::patch('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'remove'])->name('cart.remove');

Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/orders/{order}/payment-proof', [CheckoutController::class, 'proof'])->name('orders.proof');

    Route::get('/buyer/orders', [BuyerDashboardController::class, 'index'])->name('buyer.orders');
    Route::get('/buyer/orders/{order}', [BuyerDashboardController::class, 'show'])->name('buyer.orders.show');

    Route::get('/chat/{seller}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{seller}', [ChatController::class, 'store'])->name('chat.store');

    Route::get('/dashboard', function () {
        $user = auth()->user();

        if ($user->role === 'seller') {
            return redirect()->route('seller.dashboard');
        }

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('buyer.orders');
    })->name('dashboard');
});

Route::middleware(['auth', RoleMiddleware::class.':seller'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerDashboardController::class, 'index'])->name('dashboard');
    Route::get('/products', [SellerDashboardController::class, 'products'])->name('products.index');
    Route::get('/products/create', [SellerDashboardController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [SellerDashboardController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [SellerDashboardController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [SellerDashboardController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [SellerDashboardController::class, 'destroyProduct'])->name('products.destroy');

    Route::get('/orders', [SellerDashboardController::class, 'orders'])->name('orders.index');
    Route::post('/orders/{order}/status', [SellerDashboardController::class, 'updateOrder'])->name('orders.update');
});

Route::middleware(['auth', RoleMiddleware::class.':admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/sellers/{seller}/verify', [AdminDashboardController::class, 'verifySeller'])->name('sellers.verify');
    Route::delete('/products/{product}', [AdminDashboardController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/orders/{order}/confirm-payment', [AdminDashboardController::class, 'confirmPayment'])->name('orders.confirm-payment');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
