<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/katalog', [App\Http\Controllers\HomeController::class, 'katalog'])->name('katalog.index');
Route::get('/toko-florist', [App\Http\Controllers\HomeController::class, 'allShops'])->name('shops.index');

Route::get('/dashboard', [App\Http\Controllers\WishlistController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::post('/wishlist/{product}', [App\Http\Controllers\WishlistController::class, 'toggle'])->middleware('auth')->name('wishlist.toggle');
Route::get('/product/{slug}', [App\Http\Controllers\HomeController::class, 'show'])->name('product.show');
Route::get('/shop/{id}', [App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/bunga/{id}/wa-redirect', [\App\Http\Controllers\LeadController::class, 'redirectWhatsApp'])->name('product.whatsapp');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/buka-toko', [ShopController::class, 'create'])->name('shop.create');
    Route::post('/buka-toko', [ShopController::class, 'store'])->name('shop.store');

});
// Rute Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::patch('/admin/shops/{id}/approve', [AdminController::class, 'approveShop'])->name('admin.shops.approve');
});
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/products', [ProductController::class, 'index'])->name('owner.products.index');
    Route::get('/owner/products/create', [ProductController::class, 'create'])->name('owner.products.create');
    Route::post('/owner/products', [ProductController::class, 'store'])->name('owner.products.store');
    Route::get('/owner/products/{id}/edit', [ProductController::class, 'edit'])->name('owner.products.edit');
    Route::put('/owner/products/{id}', [ProductController::class, 'update'])->name('owner.products.update');
    Route::delete('/owner/products/{id}', [ProductController::class, 'destroy'])->name('owner.products.destroy');
    Route::patch('/owner/products/{id}/toggle', [ProductController::class, 'toggleStatus'])->name('owner.products.toggle');
});
require __DIR__.'/auth.php';
