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

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('wishlist.index');

Route::post('/wishlist/{product}', [App\Http\Controllers\WishlistController::class, 'toggle'])->middleware('auth')->name('wishlist.toggle');
Route::get('/product/{slug}', [App\Http\Controllers\HomeController::class, 'show'])->name('product.show');
Route::get('/shop/{id}', [App\Http\Controllers\ShopController::class, 'show'])->name('shop.show');
Route::get('/bunga/{id}/wa-redirect', [\App\Http\Controllers\LeadController::class, 'redirectWhatsApp'])->name('product.whatsapp');

// API Khusus Aksesibilitas AI
Route::post('/api/accessibility/analyze', [App\Http\Controllers\AccessibilityController::class, 'analyze'])->name('accessibility.analyze');
Route::post('/api/chatbot/ask', [App\Http\Controllers\ChatbotController::class, 'ask'])->name('chatbot.ask');

// Rute Keranjang Belanja (Cart)
Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update/{id}', [\App\Http\Controllers\CartController::class, 'updateQuantity'])->name('cart.update');
Route::delete('/cart/remove/{id}', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');
Route::get('/cart/checkout/{shop}', [\App\Http\Controllers\CartController::class, 'checkout'])->name('cart.checkout');
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Review Route
    Route::post('/product/{product}/review', [\App\Http\Controllers\ReviewController::class, 'store'])->name('review.store');
    Route::get('/buka-toko', [ShopController::class, 'create'])->name('shop.create');
    Route::post('/buka-toko', [ShopController::class, 'store'])->name('shop.store');

});
// Rute Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // NEW ROUTES FOR PHASE 2A
    Route::get('/shops', [App\Http\Controllers\AdminController::class, 'shopsIndex'])->name('shops.index');
    Route::patch('/shops/{id}/update-status', [App\Http\Controllers\AdminController::class, 'updateShopStatus'])->name('shops.update-status');
    Route::patch('/shops/{id}/approve', [App\Http\Controllers\AdminController::class, 'approveShop'])->name('shops.approve');
    Route::patch('/shops/{id}/reject', [App\Http\Controllers\AdminController::class, 'rejectShop'])->name('shops.reject');
    Route::get('/shops/{id}/edit', [App\Http\Controllers\AdminController::class, 'editShop'])->name('shops.edit');
    Route::put('/shops/{id}', [App\Http\Controllers\AdminController::class, 'updateShop'])->name('shops.update');
    
    // Manage Products
    Route::get('/products', [App\Http\Controllers\AdminController::class, 'productsIndex'])->name('products.index');
    Route::get('/products/create', [App\Http\Controllers\AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [App\Http\Controllers\AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [App\Http\Controllers\AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{id}', [App\Http\Controllers\AdminController::class, 'updateProduct'])->name('products.update');
    Route::patch('/products/{id}/hide', [App\Http\Controllers\AdminController::class, 'toggleHideProduct'])->name('products.hide');
    Route::delete('/products/{id}', [App\Http\Controllers\AdminController::class, 'deleteProduct'])->name('products.destroy');
    
    // Manage Users
    Route::get('/users', [App\Http\Controllers\AdminController::class, 'usersIndex'])->name('users.index');
    Route::patch('/users/{id}/role', [App\Http\Controllers\AdminController::class, 'updateUserRole'])->name('users.update-role');
    Route::delete('/users/{id}', [App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.destroy');
    
    // Analytics
    Route::get('/analytics', [App\Http\Controllers\AdminController::class, 'analyticsIndex'])->name('analytics.index');
});
Route::middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/owner/products', [ProductController::class, 'index'])->name('owner.products.index');
    Route::get('/owner/products/create', [ProductController::class, 'create'])->name('owner.products.create');
    Route::post('/owner/products', [ProductController::class, 'store'])->name('owner.products.store');
    Route::get('/owner/products/{id}/edit', [ProductController::class, 'edit'])->name('owner.products.edit');
    Route::put('/owner/products/{id}', [ProductController::class, 'update'])->name('owner.products.update');
    Route::delete('/owner/products/{id}', [ProductController::class, 'destroy'])->name('owner.products.destroy');
    Route::patch('/owner/products/{id}/toggle', [ProductController::class, 'toggleStatus'])->name('owner.products.toggle');
    Route::get('/owner/shop/edit', [App\Http\Controllers\ShopController::class, 'edit'])->name('owner.shop.edit');
    Route::put('/owner/shop/update', [App\Http\Controllers\ShopController::class, 'update'])->name('owner.shop.update');
});

Route::post('/accessibility-mode', function (\Illuminate\Http\Request $request) {
    if (auth()->check()) {
        auth()->user()->update(['accessibility_mode' => $request->boolean('accessibility_mode')]);
        return response()->json(['success' => true]);
    }
    return response()->json(['success' => false], 401);
})->name('accessibility.toggle');

require __DIR__.'/auth.php';
