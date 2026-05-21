<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

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

require __DIR__.'/auth.php';
