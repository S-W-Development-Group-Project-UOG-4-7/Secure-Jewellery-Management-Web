<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomOrderController;
use App\Http\Controllers\JewelleryController;
use App\Http\Controllers\LockerVerificationController;
use App\Http\Controllers\UserManagementController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

// Homepage
Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Authentication API Routes
|--------------------------------------------------------------------------
*/
Route::prefix('api/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'loginStep1']);
    Route::post('/otp', [AuthController::class, 'verifyOtp']);
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Authentication)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Logout
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Dashboards
    |--------------------------------------------------------------------------
    */

    // Admin Dashboard
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Customer Studio
    Route::get('/customer/studio', function () {
        return redirect()->route('custom.create');
    })->name('customer.studio');

    /*
    |--------------------------------------------------------------------------
    | Jewellery Management
    |--------------------------------------------------------------------------
    */
    Route::resource('jewellery', JewelleryController::class)
        ->except(['show']);

    /*
    |--------------------------------------------------------------------------
    | Locker Verification
    |--------------------------------------------------------------------------
    */

    // Before storage verification
    Route::get('/locker-verification/before', [LockerVerificationController::class, 'beforeForm'])
        ->name('locker.verify.before');

    Route::post('/locker-verification/before', [LockerVerificationController::class, 'storeBefore'])
        ->name('locker.verify.before.store');

    // After storage verification
    Route::get('/locker-verification/after', [LockerVerificationController::class, 'afterForm'])
        ->name('locker.verify.after');

    Route::post('/locker-verification/after', [LockerVerificationController::class, 'storeAfter'])
        ->name('locker.verify.after.store');

    // Verification results
    Route::get('/locker-verification/results/{verification}', [LockerVerificationController::class, 'results'])
        ->name('locker.verify.results');

    /*
    |--------------------------------------------------------------------------
    | Custom Orders (Customer)
    |--------------------------------------------------------------------------
    */

    // Create custom order
    Route::get('/custom-order/create', [CustomOrderController::class, 'create'])
        ->name('custom.create');

    Route::post('/custom-order/store', [CustomOrderController::class, 'store'])
        ->name('custom.store');

    // Order tracking
    Route::get('/my-orders', [CustomOrderController::class, 'index'])
        ->name('custom.index');

    Route::get('/order/{id}', [CustomOrderController::class, 'show'])
        ->name('custom.show');

    /*
    |--------------------------------------------------------------------------
    | Manager Approval
    |--------------------------------------------------------------------------
    */
    Route::get('/manager/approvals', [CustomOrderController::class, 'managerIndex'])
        ->name('manager.approvals');

    Route::post('/manager/approve/{id}', [CustomOrderController::class, 'approve'])
        ->name('manager.approve');

    /*
    |--------------------------------------------------------------------------
    | Admin - User Management
    |--------------------------------------------------------------------------
    */
    Route::prefix('admin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserManagementController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserManagementController::class, 'store'])
            ->name('users.store');

        Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])
            ->name('users.role.update');

        Route::patch('/users/{user}/toggle', [UserManagementController::class, 'toggleActive'])
            ->name('users.toggle');
    });

});
