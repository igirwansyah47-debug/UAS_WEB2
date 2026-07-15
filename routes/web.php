<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('guest')->group(function () {
    Route::get('/', [LoginController::class, 'index'])->name('login');
    Route::post('/authenticate', [LoginController::class, 'authenticate'])->name('login.authenticate');
});

Route::middleware('auth')->group(function () {
    Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');
    Route::post('/switch-user', [LoginController::class, 'switchUser'])->name('login.switch_user');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
    Route::get('/dashboard/show', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard/edit', [DashboardController::class, 'edit'])->name('dashboard.edit');
    Route::put('/dashboard/update', [DashboardController::class, 'update'])->name('dashboard.update');

    Route::resource('/user', UserController::class)->middleware('role:superadmin');
    Route::resource('/property', App\Http\Controllers\PropertyController::class)->middleware('role:superadmin,owner');
    Route::resource('/room', App\Http\Controllers\RoomController::class)->middleware('role:superadmin,owner');
    Route::resource('/facility', App\Http\Controllers\FacilityController::class)->middleware('role:superadmin');
    
    Route::resource('/booking', App\Http\Controllers\BookingController::class)->only(['index', 'create', 'store', 'show'])->middleware('role:superadmin,owner,tenant');
    Route::post('/booking/{booking}/mark-as-paid', [App\Http\Controllers\BookingController::class, 'markAsPaid'])->name('booking.markAsPaid')->middleware('role:superadmin,owner');

    Route::post('/review', [App\Http\Controllers\ReviewController::class, 'store'])->name('review.store')->middleware('role:tenant');
    Route::resource('/complaint', App\Http\Controllers\ComplaintController::class)->only(['index', 'create', 'store', 'show', 'update'])->middleware('role:superadmin,owner,tenant');
    Route::get('/wishlist', [App\Http\Controllers\WishlistController::class, 'index'])->name('wishlist.index')->middleware('role:tenant');
    Route::post('/wishlist/toggle', [App\Http\Controllers\WishlistController::class, 'toggle'])->name('wishlist.toggle')->middleware('role:tenant');

    Route::get('/tenant-management', [App\Http\Controllers\TenantManagementController::class, 'index'])->name('tenant_management.index')->middleware('role:owner');
    Route::get('/tenant-management/{id}', [App\Http\Controllers\TenantManagementController::class, 'show'])->name('tenant_management.show')->middleware('role:owner');
    Route::post('/tenant-management/{booking}/complete', [App\Http\Controllers\TenantManagementController::class, 'completeBooking'])->name('tenant_management.completeBooking')->middleware('role:owner');

    Route::get('/verification', [App\Http\Controllers\VerificationController::class, 'index'])->name('verification.index')->middleware('role:superadmin');
    Route::post('/verification/{property}/approve', [App\Http\Controllers\VerificationController::class, 'approve'])->name('verification.approve')->middleware('role:superadmin');
    Route::post('/verification/{property}/reject', [App\Http\Controllers\VerificationController::class, 'reject'])->name('verification.reject')->middleware('role:superadmin');

    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting/{setting}/update', [SettingController::class, 'update'])->name('setting.update');
});
