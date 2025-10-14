<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BlogCategoryController;
use App\Http\Controllers\Api\BlogPostController;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

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