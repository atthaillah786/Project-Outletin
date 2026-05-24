<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;

use App\Http\Controllers\FranchiseeDashboardController;
use App\Http\Controllers\FranchisorDashboardController;
use App\Http\Controllers\SuperadminDashboardController;

use App\Http\Controllers\BrandRegistrationController;
use App\Http\Controllers\SuperadminBrandVerificationController;

use App\Http\Controllers\BrandCrudController;
use App\Http\Controllers\OutletCrudController;
use App\Http\Controllers\ProdukCrudController;
use App\Http\Controllers\FranchiseeProdukController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/outlet', function () {
    return view('outlet');
})->name('outlet');

Route::get('/about', function () {
    return view('about');
})->name('about');


/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])
    ->name('register');

Route::post('/register', [AuthController::class, 'register'])
    ->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT BY ROLE
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->get('/dashboard', function () {
    $user = Auth::user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role === 'superadmin') {
        return redirect()->route('superadmin.dashboard');
    }

    if ($user->role === 'franchisor') {
        return redirect()->route('franchisor.dashboard');
    }

    if ($user->role === 'franchise') {
        return redirect()->route('franchisee.dashboard');
    }

    return redirect()->route('home');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| AUTHENTICATED ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/superadmin', [SuperadminDashboardController::class, 'index'])
        ->name('superadmin.dashboard');

    Route::post('/dashboard/superadmin/brands/{id}/approve', [SuperadminDashboardController::class, 'approveBrand'])
        ->name('superadmin.brands.approve');

    Route::post('/dashboard/superadmin/brands/{id}/reject', [SuperadminDashboardController::class, 'rejectBrand'])
        ->name('superadmin.brands.reject');


    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN BRAND VERIFICATION
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/superadmin/brand-verification', [SuperadminBrandVerificationController::class, 'index'])
        ->name('superadmin.brand.verification');

    Route::post('/dashboard/superadmin/brand-verification/{id}/approve', [SuperadminBrandVerificationController::class, 'approve'])
        ->name('superadmin.brand.approve');

    Route::post('/dashboard/superadmin/brand-verification/{id}/reject', [SuperadminBrandVerificationController::class, 'reject'])
        ->name('superadmin.brand.reject');


    /*
    |--------------------------------------------------------------------------
    | FRANCHISOR DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/franchisor', [FranchisorDashboardController::class, 'index'])
        ->name('franchisor.dashboard');

    Route::post('/dashboard/franchisor/outlets/{id}/approve', [FranchisorDashboardController::class, 'approveApplication'])
        ->name('franchisor.applications.approve');

    Route::post('/dashboard/franchisor/outlets/{id}/reject', [FranchisorDashboardController::class, 'rejectApplication'])
        ->name('franchisor.applications.reject');


    /*
    |--------------------------------------------------------------------------
    | FRANCHISOR BRAND REGISTRATION
    |--------------------------------------------------------------------------
    */

    Route::get('/brand/register', [BrandRegistrationController::class, 'create'])
        ->name('brand.registration.create');

    Route::post('/brand/register', [BrandRegistrationController::class, 'store'])
        ->name('brand.registration.store');


    /*
    |--------------------------------------------------------------------------
    | FRANCHISEE DASHBOARD
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/franchisee', [FranchiseeDashboardController::class, 'index'])
        ->name('franchisee.dashboard');

    Route::get('/dashboard/franchisee/brands/{id}/apply', [FranchiseeDashboardController::class, 'createOutletApplication'])
        ->name('franchisee.outlets.create');

    Route::post('/dashboard/franchisee/brands/{id}/apply', [FranchiseeDashboardController::class, 'storeOutletApplication'])
        ->name('franchisee.outlets.store');


    /*
    |--------------------------------------------------------------------------
    | FRANCHISEE VIEW PRODUK BRAND
    |--------------------------------------------------------------------------
    | Pemilik outlet hanya melihat produk dari brand yang sudah terhubung
    | melalui outlet berstatus approved.
    |--------------------------------------------------------------------------
    */

    Route::get('/dashboard/franchisee/produk', [FranchiseeProdukController::class, 'index'])
        ->name('franchisee.produk.index');


    /*
    |--------------------------------------------------------------------------
    | CRUD MANAGEMENT
    |--------------------------------------------------------------------------
    | Brand  : /dashboard/manage/brands
    | Outlet : /dashboard/manage/outlets
    | Produk : /dashboard/manage/produk
    |--------------------------------------------------------------------------
    */

    Route::prefix('dashboard/manage')
        ->name('manage.')
        ->group(function () {

            Route::resource('brands', BrandCrudController::class)
                ->except(['show']);

            Route::resource('outlets', OutletCrudController::class)
                ->except(['show']);

            Route::resource('produk', ProdukCrudController::class)
                ->except(['show']);
        });
});