<?php

Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\VerificationController;


Route::get('/test', function() {
    return response()->json(['message' => 'API is working!']);
});

// Public routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/send-verification', [VerificationController::class, 'sendCode']);
Route::post('/verify-code', [VerificationController::class, 'verifyCode']);

// Protected routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Orders
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    
    // Profile
    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);
});