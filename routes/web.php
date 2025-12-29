<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DesignStudioController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('landing');
})->name('home');

// Updated: Ensure these views exist in resources/views/ or resources/views/auth/
Route::get('/login', function () {
    return view('login'); // Make sure file is at resources/views/login.blade.php
})->name('login');

Route::get('/register', function () {
    return view('register'); // Make sure file is at resources/views/register.blade.php
})->name('register');


/*
|--------------------------------------------------------------------------
| Authentication Logic
|--------------------------------------------------------------------------
*/
Route::post('/login', [AuthController::class, 'login']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| CUSTOMER ROUTES (Default Role = customer)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {

    // ✅ FIX: This points to the Controller, which loads the $history data
    Route::get('/design-studio', [DesignStudioController::class, 'index'])
        ->name('design.studio');

    // ✅ AI Design Generator
    Route::post('/design-studio/generate', [DesignStudioController::class, 'generate'])
        ->name('design.generate');
});


/*
|--------------------------------------------------------------------------
| ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('admin.dashboard');
});


/*
|--------------------------------------------------------------------------
| MANAGER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:manager'])
    ->prefix('manager')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('manager.dashboard');
        })->name('manager.dashboard');
});


/*
|--------------------------------------------------------------------------
| SUPPLIER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:supplier'])
    ->prefix('supplier')
    ->group(function () {

        Route::get('/dashboard', function () {
            return view('supplier.dashboard');
        })->name('supplier.dashboard');
});