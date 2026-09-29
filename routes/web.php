<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;

// --- Frontend Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
Route::post('/order/process', [HomeController::class, 'processOrder'])->name('order.process');
Route::get('/track', [HomeController::class, 'trackOrder'])->name('order.track');

// --- Admin Authentication Routes ---
Route::get('/admin/login', [AdminController::class, 'showLogin'])->name('admin.login');
Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');
Route::post('/admin/login', [AdminController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// --- Admin Protected Routes ---
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    // --- Products Management Routes ---
    Route::get('/products', [AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{id}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
    Route::post('/products/{id}', [AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{id}', [AdminController::class, 'destroyProduct'])->name('products.destroy');
    Route::post('/products/{id}/toggle', [AdminController::class, 'toggleProductStock'])->name('products.toggle');

    Route::get('/gemini', [AdminController::class, 'gemini'])->name('gemini');
    Route::post('/gemini', [AdminController::class, 'updateGemini'])->name('gemini.update');
    Route::get('/links', [AdminController::class, 'links'])->name('links');
    Route::post('/links', [AdminController::class, 'storeLinks'])->name('links.store');
    Route::delete('/links/{id}', [AdminController::class, 'deleteLink'])->name('links.delete');
    Route::post('/links/clear-unsold', [AdminController::class, 'clearUnsoldLinks'])->name('links.clear-unsold');
    Route::post('/links/bulk-delete', [AdminController::class, 'bulkDeleteLinks'])->name('links.bulk-delete');
    Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/password', [AdminController::class, 'updatePassword'])->name('password.update');
});
