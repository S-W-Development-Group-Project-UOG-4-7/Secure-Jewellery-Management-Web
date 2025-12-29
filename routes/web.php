<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\JewelleryController;
use App\Http\Controllers\LockerController;
use App\Http\Controllers\LockerVerificationController;
use App\Http\Controllers\AdminUserController;

Route::get('/', [LandingController::class, 'index'])->name('landing');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/otp', [AuthController::class, 'showOtp'])->name('otp.show');
    Route::post('/otp/verify', [AuthController::class, 'verifyOtp'])->name('otp.verify');
    Route::post('/otp/resend', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::resource('customers', CustomerController::class);

    Route::resource('suppliers', SupplierController::class);

    Route::resource('deliveries', DeliveryController::class);

    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::put('/stock/{stock}/update', [StockController::class, 'update'])->name('stock.update');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [ProfileController::class, 'showChangePassword'])->name('profile.change-password');
    Route::post('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.change-password.update');

    Route::resource('jewellery', JewelleryController::class);

    Route::resource('lockers', LockerController::class);

    Route::get('/locker-verification', [LockerVerificationController::class, 'index'])->name('locker-verification.index');
    Route::get('/locker-verification/before', [LockerVerificationController::class, 'beforeStorage'])->name('locker-verification.before');
    Route::post('/locker-verification/before', [LockerVerificationController::class, 'storeBeforeStorage'])->name('locker-verification.before.store');
    Route::get('/locker-verification/after/{verification}', [LockerVerificationController::class, 'afterStorage'])->name('locker-verification.after');
    Route::post('/locker-verification/after/{verification}', [LockerVerificationController::class, 'storeAfterStorage'])->name('locker-verification.after.store');
    Route::get('/locker-verification/results/{verification}', [LockerVerificationController::class, 'results'])->name('locker-verification.results');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [AdminUserController::class, 'create'])->name('users.create');
        Route::post('/users', [AdminUserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [AdminUserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [AdminUserController::class, 'update'])->name('users.update');
        Route::post('/users/{user}/toggle-status', [AdminUserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });
});
