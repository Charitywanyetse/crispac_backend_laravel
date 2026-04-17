<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;

// Public routes
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (requires authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);
    
    // Customer dashboard
    Route::get('/dashboard', [AdminController::class, 'customerDashboard']);
    
    // Admin routes (requires admin role)
    Route::prefix('admin')->middleware(['admin'])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard']);
        Route::get('/products', [AdminController::class, 'products']);
        Route::get('/orders', [AdminController::class, 'orders']);
        Route::get('/customers', [AdminController::class, 'customers']);
        Route::get('/inventory', [AdminController::class, 'inventory']);
        Route::get('/production', [AdminController::class, 'production']);
        Route::get('/finance', [AdminController::class, 'finance']);
        Route::get('/reports', [AdminController::class, 'reports']);
    });
});