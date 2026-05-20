<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OutletPageController;
use App\Http\Controllers\FranchisorDashboardController;
use App\Http\Controllers\BrandRegistrationController;
use App\Http\Controllers\SuperadminBrandVerificationController;
use App\Http\Controllers\SuperadminDashboardController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/outlet', [OutletPageController::class, 'index'])->name('outlet');

Route::get('/about', function () {
    return view('about');
})->name('about');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard/franchisor', [FranchisorDashboardController::class, 'index'])
        ->name('franchisor.dashboard');

    Route::post('/dashboard/franchisor/applications/{id}/approve', [FranchisorDashboardController::class, 'approveApplication'])
        ->name('franchisor.applications.approve');

    Route::post('/dashboard/franchisor/applications/{id}/reject', [FranchisorDashboardController::class, 'rejectApplication'])
        ->name('franchisor.applications.reject');

    Route::get('/brand/register', [BrandRegistrationController::class, 'create'])
        ->name('brand.registration.create');

    Route::post('/brand/register', [BrandRegistrationController::class, 'store'])
        ->name('brand.registration.store');

    Route::get('/superadmin/brand-verification', [SuperadminBrandVerificationController::class, 'index'])
        ->name('superadmin.brand.verification');

    Route::post('/superadmin/brand-verification/{id}/approve', [SuperadminBrandVerificationController::class, 'approve'])
        ->name('superadmin.brand.approve');

    Route::post('/superadmin/brand-verification/{id}/reject', [SuperadminBrandVerificationController::class, 'reject'])
        ->name('superadmin.brand.reject');

    Route::get('/dashboard/superadmin', [SuperadminDashboardController::class, 'index'])
        ->name('superadmin.dashboard');

    Route::post('/dashboard/superadmin/brands/{id}/approve', [SuperadminDashboardController::class, 'approveBrand'])
        ->name('superadmin.brands.approve');

    Route::post('/dashboard/superadmin/brands/{id}/reject', [SuperadminDashboardController::class, 'rejectBrand'])
        ->name('superadmin.brands.reject');
});