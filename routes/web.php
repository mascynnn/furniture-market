<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;

/*
|--------------------------------------------------------------------------
| CasaForma — Web Routes (Bagian Anggun)
|--------------------------------------------------------------------------
*/

// ── Beranda ──────────────────────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');

// ── AUTENTIKASI ──────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── KATALOG PRODUK ───────────────────────────────────────────────────
Route::get('/products',          [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}',   [ProductController::class, 'show'])->name('products.show');

// ── REVIEW & RATING ──────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews',  [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}',           [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ── WISHLIST ─────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/wishlist',                       [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}/toggle',     [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}',          [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist',                     [WishlistController::class, 'clear'])->name('wishlist.clear');
});

// ── NOTIFIKASI ───────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications',                  [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read',        [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all',        [NotificationController::class, 'readAll'])->name('notifications.readAll');
});

/*
|--------------------------------------------------------------------------
| Route stub — dikerjakan anggota lain
|--------------------------------------------------------------------------
*/

// Najwa: cart, checkout, order, shipping, laporan
Route::get('/cart', fn() => abort(404))->name('cart.index');
Route::post('/cart/{product}', fn() => abort(404))->name('cart.add');

// Makasin: payment, chat, seller dashboard, kelola produk, kelola pesanan
Route::get('/seller/dashboard', fn() => abort(404))->name('seller.dashboard');

// Profile (bisa dikerjakan siapa saja)
Route::get('/profile/edit', fn() => abort(404))->middleware('auth')->name('profile.edit');
