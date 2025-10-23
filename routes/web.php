<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\BisnisController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogPostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManagementUserController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('my-profile', UserProfileController::class);
    Route::resource('bisnis', BisnisController::class);
    Route::post('/set', [BisnisController::class, 'set'])->name('set');
    Route::resource('product-categories', ProductCategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('transactions', TransactionController::class);
    Route::patch('/transactions/{transaction}/confirm', [TransactionController::class, 'confirm'])->name('transactions.confirm');
    Route::get('/transactions/{transaction}/print', [TransactionController::class, 'print'])->name('transactions.print');
    Route::get('/transactions-list', [TransactionController::class, 'list'])->name('transactions.list');
    Route::get('/konsultasi', [AiChatController::class, 'index'])->name('ai.chat');
    Route::post('/konsultasi/send', [AiChatController::class, 'send'])->name('ai.chat.send');
    Route::resource('blogPosts', BlogPostController::class);
    Route::get('/blogPost/{slug}', [BlogPostController::class, 'read'])->name('blogPosts.read');
    Route::get('/billing', [BillingController::class, 'index'])->name('billing.index');
    Route::get('/billing/checkout/{planId}', [BillingController::class, 'checkout'])->name('billing.checkout');
    Route::get('/billing/pending/{id}', [BillingController::class, 'pending'])->name('billing.pending');
    Route::get(
        '/billing/success/{plan}',
        fn($plan) =>
        view('main.billing.success', ['plan' => \App\Models\Plan::findOrFail($plan)])
    )->name('billing.success');
    Route::post('/billing/callback', [BillingController::class, 'callback'])->name('billing.callback');
    Route::get('/cart', [HomeController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add', [HomeController::class, 'add'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [HomeController::class, 'remove'])->name('cart.remove');
});

Route::middleware('auth', 'admin')->group(function () {
    Route::get('/blogPost-list', [BlogPostController::class, 'list'])->name('blogPosts.list');
    Route::resource('blogCategories', BlogCategoryController::class);
    Route::resource('users', ManagementUserController::class);
    Route::resource('admin/plans', PlanController::class);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])
    ->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])
    ->name('google.callback');


require __DIR__.'/auth.php';
