<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Gabungan (Anggun + Najwa)
|--------------------------------------------------------------------------
*/

// ──────────────────────────────────────────────────────────────────────────────
// BERANDA
// ──────────────────────────────────────────────────────────────────────────────
Route::get('/', fn() => view('home'))->name('home');

// ──────────────────────────────────────────────────────────────────────────────
// AUTENTIKASI (Anggun)
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ──────────────────────────────────────────────────────────────────────────────
// KATALOG PRODUK (Anggun)
// ──────────────────────────────────────────────────────────────────────────────
Route::get('/products',          [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}',   [ProductController::class, 'show'])->name('products.show');

// ──────────────────────────────────────────────────────────────────────────────
// REVIEW & RATING (Anggun)
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::post('/products/{product}/reviews',  [ReviewController::class, 'store'])->name('reviews.store');
    Route::delete('/reviews/{review}',           [ReviewController::class, 'destroy'])->name('reviews.destroy');
});

// ──────────────────────────────────────────────────────────────────────────────
// WISHLIST (Anggun)
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/wishlist',                       [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}/toggle',     [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::delete('/wishlist/{wishlist}',          [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    Route::delete('/wishlist',                     [WishlistController::class, 'clear'])->name('wishlist.clear');
});

// ──────────────────────────────────────────────────────────────────────────────
// NOTIFIKASI (Anggun)
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/notifications',                  [NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/{id}/read',        [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all',        [NotificationController::class, 'readAll'])->name('notifications.readAll');
});

// ──────────────────────────────────────────────────────────────────────────────
// PENCARIAN & FILTER (Najwa)
// ──────────────────────────────────────────────────────────────────────────────
Route::get('/cari', [SearchController::class, 'index'])->name('search.index');

// ──────────────────────────────────────────────────────────────────────────────
// KERANJANG BELANJA (Najwa) — session-based, tanpa auth
// ──────────────────────────────────────────────────────────────────────────────
Route::prefix('keranjang')->name('cart.')->group(function () {
    Route::get('/',               [CartController::class, 'index'])->name('index');
    Route::post('/tambah',        [CartController::class, 'add'])->name('add');
    Route::patch('/{id}',         [CartController::class, 'update'])->name('update');
    Route::delete('/{id}',        [CartController::class, 'remove'])->name('remove');
    Route::delete('/',            [CartController::class, 'clear'])->name('clear');
});

// ──────────────────────────────────────────────────────────────────────────────
// CHECKOUT, PESANAN, PENGIRIMAN, LAPORAN (Najwa) — semua butuh auth
// ──────────────────────────────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/',                 [CheckoutController::class, 'index'])->name('index');
        Route::post('/proses',          [CheckoutController::class, 'process'])->name('process');
        Route::get('/konfirmasi/{id}',  [CheckoutController::class, 'confirm'])->name('confirm');
    });

    // Pesanan
    Route::prefix('pesanan')->name('orders.')->group(function () {
        Route::get('/',           [OrderController::class, 'index'])->name('index');
        Route::get('/{id}',       [OrderController::class, 'show'])->name('show');
        Route::patch('/{id}/batal', [OrderController::class, 'cancel'])->name('cancel');
    });

    // Pengiriman & Tracking
    Route::prefix('pengiriman')->name('shipping.')->group(function () {
        Route::get('/lacak/{orderId}',       [ShippingController::class, 'track'])->name('track');
        Route::patch('/resi/{orderId}',      [ShippingController::class, 'updateResi'])->name('update-resi');
    });

    // Laporan Penjualan
    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/',          [ReportController::class, 'index'])->name('index');
        Route::get('/ekspor',    [ReportController::class, 'export'])->name('export');
    });
});

// ──────────────────────────────────────────────────────────────────────────────
// API RAJAONGKIR (untuk keperluan checkout via JS)
// ──────────────────────────────────────────────────────────────────────────────
Route::prefix('api/rajaongkir')->name('api.rajaongkir.')->group(function () {
    Route::get('/cities/{provinceId}',   [ShippingController::class, 'apiCities'])->name('cities');
    Route::get('/shipping-options',      [ShippingController::class, 'apiShippingOptions'])->name('shipping-options');
});

// ──────────────────────────────────────────────────────────────────────────────
// ROUTE STUB UNTUK YANG BELUM DIIMPLEMENTASIKAN
// ──────────────────────────────────────────────────────────────────────────────
// Makasin: payment, chat, seller dashboard, kelola produk, kelola pesanan
Route::get('/seller/dashboard', fn() => abort(404))->name('seller.dashboard');

// Profile (bisa dikerjakan siapa saja)
Route::get('/profile/edit', fn() => abort(404))->middleware('auth')->name('profile.edit');

/*
Catatan: Stub untuk cart.index dan cart.add sudah tidak diperlukan karena sudah
didefinisikan di route prefix 'keranjang' dengan nama 'cart.index' dan 'cart.add'.
*/