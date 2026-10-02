<?php

use Illuminate\Support\Facades\Route;

// Customer & Public Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\Auth\CustomerAuthController;

// Admin Controllers
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CustomerController as AdminCustomerController;
use App\Http\Controllers\Admin\OfferController as AdminOfferController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\BranchController as AdminBranchController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;

/*
|--------------------------------------------------------------------------
| Public Storefront Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');

// Shopping Cart
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{key}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove/{key}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/success/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

// Orders
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
Route::get('/orders/track', [OrderController::class, 'track'])->name('orders.track');
Route::get('/orders/{orderNumber}', [OrderController::class, 'show'])->name('orders.show');

// Wishlist
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/toggle/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');

// Product Reviews
Route::post('/product/{productId}/review', [ReviewController::class, 'store'])->name('reviews.store');

// Static Pages & Contact
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/branches', [PageController::class, 'branches'])->name('pages.branches');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('pages.contact.submit');

// Customer Auth
Route::get('/login', [CustomerAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('login.submit');
Route::get('/register', [CustomerAuthController::class, 'showRegister'])->name('register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [CustomerAuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/customer/profile', [CustomerAuthController::class, 'profile'])->name('customer.profile');
    Route::post('/customer/profile', [CustomerAuthController::class, 'updateProfile'])->name('customer.profile.update');
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('admin.login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Protected Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Products
    Route::get('/products', [AdminProductController::class, 'index'])->name('admin.products.index');
    Route::get('/products/create', [AdminProductController::class, 'create'])->name('admin.products.create');
    Route::post('/products', [AdminProductController::class, 'store'])->name('admin.products.store');
    Route::get('/products/{id}/edit', [AdminProductController::class, 'edit'])->name('admin.products.edit');
    Route::put('/products/{id}', [AdminProductController::class, 'update'])->name('admin.products.update');
    Route::delete('/products/{id}', [AdminProductController::class, 'destroy'])->name('admin.products.destroy');
    Route::post('/products/{id}/toggle-active', [AdminProductController::class, 'toggleActive'])->name('admin.products.toggle');
    Route::delete('/products/images/{imageId}', [AdminProductController::class, 'deleteImage'])->name('admin.products.deleteImage');

    // Categories
    Route::get('/categories', [AdminCategoryController::class, 'index'])->name('admin.categories.index');
    Route::get('/categories/create', [AdminCategoryController::class, 'create'])->name('admin.categories.create');
    Route::post('/categories', [AdminCategoryController::class, 'store'])->name('admin.categories.store');
    Route::get('/categories/{id}/edit', [AdminCategoryController::class, 'edit'])->name('admin.categories.edit');
    Route::put('/categories/{id}', [AdminCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [AdminCategoryController::class, 'destroy'])->name('admin.categories.destroy');
    Route::post('/categories/{id}/toggle-active', [AdminCategoryController::class, 'toggleActive'])->name('admin.categories.toggle');

    // Orders
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
    Route::get('/orders/{id}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.updateStatus');

    // Customers
    Route::get('/customers', [AdminCustomerController::class, 'index'])->name('admin.customers.index');
    Route::get('/customers/{id}', [AdminCustomerController::class, 'show'])->name('admin.customers.show');
    Route::post('/customers/{id}/toggle-status', [AdminCustomerController::class, 'toggleStatus'])->name('admin.customers.toggle');
    Route::delete('/customers/{id}', [AdminCustomerController::class, 'destroy'])->name('admin.customers.destroy');

    // Offers & Sales
    Route::get('/offers', [AdminOfferController::class, 'index'])->name('admin.offers.index');
    Route::post('/offers', [AdminOfferController::class, 'store'])->name('admin.offers.store');
    Route::post('/offers/{productId}/remove', [AdminOfferController::class, 'remove'])->name('admin.offers.remove');

    // Banners
    Route::get('/banners', [AdminBannerController::class, 'index'])->name('admin.banners.index');
    Route::get('/banners/create', [AdminBannerController::class, 'create'])->name('admin.banners.create');
    Route::post('/banners', [AdminBannerController::class, 'store'])->name('admin.banners.store');
    Route::get('/banners/{id}/edit', [AdminBannerController::class, 'edit'])->name('admin.banners.edit');
    Route::put('/banners/{id}', [AdminBannerController::class, 'update'])->name('admin.banners.update');
    Route::delete('/banners/{id}', [AdminBannerController::class, 'destroy'])->name('admin.banners.destroy');
    Route::post('/banners/{id}/toggle', [AdminBannerController::class, 'toggleActive'])->name('admin.banners.toggle');

    // Branches
    Route::get('/branches', [AdminBranchController::class, 'index'])->name('admin.branches.index');
    Route::get('/branches/create', [AdminBranchController::class, 'create'])->name('admin.branches.create');
    Route::post('/branches', [AdminBranchController::class, 'store'])->name('admin.branches.store');
    Route::get('/branches/{id}/edit', [AdminBranchController::class, 'edit'])->name('admin.branches.edit');
    Route::put('/branches/{id}', [AdminBranchController::class, 'update'])->name('admin.branches.update');
    Route::delete('/branches/{id}', [AdminBranchController::class, 'destroy'])->name('admin.branches.destroy');
    Route::post('/branches/{id}/toggle', [AdminBranchController::class, 'toggleActive'])->name('admin.branches.toggle');

    // Product Reviews & Ratings
    Route::get('/reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
    Route::post('/reviews/{id}/status', [AdminReviewController::class, 'updateStatus'])->name('admin.reviews.updateStatus');
    Route::post('/reviews/{id}/toggle-featured', [AdminReviewController::class, 'toggleFeatured'])->name('admin.reviews.toggleFeatured');
    Route::delete('/reviews/{id}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');

    // Store Settings
    Route::get('/settings', [AdminSettingController::class, 'index'])->name('admin.settings.index');
    Route::post('/settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
});
