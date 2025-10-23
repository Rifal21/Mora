<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogPostController;
use App\Http\Controllers\Api\DokuNotificationController;
use App\Models\PaymentTransaction;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::post('/doku/notif-hook', [DokuNotificationController::class, 'handleNotification']);
Route::get('/transaction/status/{invoice}', function ($invoice) {
    $transaction = PaymentTransaction::where('invoice_number', $invoice)->first();

    return response()->json([
        'status' => $transaction ? $transaction->status : 'not_found',
    ]);
});

// Public routes
Route::get('/categories', [BlogCategoryController::class, 'index']);
Route::get('/posts', [BlogPostController::class, 'index']);
Route::get('/posts/{slug}', [BlogPostController::class, 'show']);

// Protected routes (admin)
Route::middleware('auth:sanctum')->group(function () {
Route::post('/posts', [BlogPostController::class, 'store']);
Route::put('/posts/{id}', [BlogPostController::class, 'update']);
Route::delete('/posts/{id}', [BlogPostController::class, 'destroy']);
});