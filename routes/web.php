<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ZiniPayController;

// --- Frontend Public Routes ---
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'productDetails'])->name('product.details');
Route::get('/order/{order_number}/success', [HomeController::class, 'orderSuccess'])->name('order.success');
Route::get('/track', [HomeController::class, 'trackOrder'])->name('order.track');

// --- ZiniPay Payment Gateway Routes ---
Route::post('/payment/zinipay/init', [ZiniPayController::class, 'initPayment'])->name('payment.zinipay.init');
Route::match(['get', 'post'], '/payment/zinipay/callback', [ZiniPayController::class, 'handleCallback'])->name('payment.zinipay.callback');
Route::post('/payment/zinipay/webhook', [ZiniPayController::class, 'handleWebhook'])->name('payment.zinipay.webhook');
Route::get('/payment/zinipay/cancel', [ZiniPayController::class, 'handleCancel'])->name('payment.zinipay.cancel');

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
    Route::delete('/orders/{id}', [AdminController::class, 'destroyOrder'])->name('orders.destroy');
    Route::post('/orders/bulk-delete', [AdminController::class, 'bulkDeleteOrders'])->name('orders.bulk-delete');
    Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
    Route::post('/password', [AdminController::class, 'updatePassword'])->name('password.update');
});
