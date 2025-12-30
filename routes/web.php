<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\SystemLogController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application.
|
*/

// 1. Public Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Authentication API
Route::prefix('api/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginStep1']);
    Route::post('/otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register', [AuthController::class, 'register']);
});

// 3. Protected Routes (Require Login)
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- DASHBOARDS ---

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Customer Studio (Redirects to Order Creation)
    Route::get('/customer/studio', function () {
        return redirect()->route('custom.create');
    })->name('customer.studio');

    // --- FEATURE 1.1: CUSTOM ORDER UI ---
    Route::get('/custom-order/create', [CustomOrderController::class, 'create'])->name('custom.create');
    Route::post('/custom-order/store', [CustomOrderController::class, 'store'])->name('custom.store');

    // --- FEATURE 1.3: ORDER TRACKING UI ---
    Route::get('/my-orders', [CustomOrderController::class, 'index'])->name('custom.index');
    Route::get('/order/{id}', [CustomOrderController::class, 'show'])->name('custom.show');

    // --- FEATURE 1.2: MANAGER APPROVAL UI ---
    Route::get('/manager/approvals', [CustomOrderController::class, 'managerIndex'])->name('manager.approvals');
    Route::post('/manager/approve/{id}', [CustomOrderController::class, 'approve'])->name('manager.approve');

    // --- FEATURE 2.0: SYSTEM LOGS ---
    Route::get('/logs', [SystemLogController::class, 'index'])
        ->name('logs.index');
});
