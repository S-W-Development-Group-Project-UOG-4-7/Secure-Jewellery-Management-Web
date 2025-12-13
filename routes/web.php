<?php

use Illuminate\Support\Facades\Route;
// --- THESE 2 LINES FIX THE RED ERRORS ---
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomOrderController;

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

// 3. Protected Routes
Route::middleware(['auth'])->group(function () {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Admin Dashboard
    Route::get('/admin/dashboard', function() {
        return "<div style='padding:50px; font-family:sans-serif;'>
                    <h1>Admin Dashboard</h1>
                    <p>Welcome, " . auth()->user()->username . "</p>
                    <hr>
                    <p><a href='".route('manager.approvals')."'>Manage Approvals</a></p>
                    <form method='POST' action='".route('logout')."'>
                        ".csrf_field()."
                        <button type='submit' style='color:red; cursor:pointer;'>Logout</button>
                    </form>
                </div>";
    })->name('admin.dashboard');

    // Customer Studio
    Route::get('/customer/studio', function() {
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

});