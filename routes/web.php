<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// 1. Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Auth API Routes (Connected to Controller)
Route::prefix('api/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginStep1']);
    Route::post('/otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Dashboard Placeholders (MUST have names to match Controller logic)
Route::get('/admin/dashboard', function() { 
    return "<h1>Welcome Admin!</h1><p>This is the Admin Dashboard.</p>"; 
})->name('admin.dashboard'); // <--- CRITICAL: Added Name

Route::get('/customer/studio', function() { 
    return "<h1>Welcome Customer!</h1><p>This is the Design Studio.</p>"; 
})->name('customer.studio'); // <--- CRITICAL: Added Name