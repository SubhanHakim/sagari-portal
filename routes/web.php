<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

// Landing page/login
Route::view('/', 'auth.login');

// Dashboard user biasa
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Resource dashboard dengan permission sederhana
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    // View users - permission: 'view'
    Route::get('/users', [UserController::class, 'index'])
        ->middleware('permission:view')
        ->name('users.index');

    // Create users - permission: 'create'
    Route::get('/users/create', [UserController::class, 'create'])
        ->middleware('permission:create')
        ->name('users.create');
    Route::post('/users', [UserController::class, 'store'])
        ->middleware('permission:create')
        ->name('users.store');

    // Edit users - permission: 'update'
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])
        ->middleware('permission:update')
        ->name('users.edit');
    Route::post('/users/{user}/update', [UserController::class, 'update'])
        ->middleware('permission:update')
        ->name('users.update');

    // Delete users - permission: 'delete'
    Route::delete('/users/{user}', [UserController::class, 'destroy'])
        ->middleware('permission:delete')
        ->name('users.destroy');

    // Role & Position - permission: 'admin access' (khusus admin)
    Route::get('/roles', [UserController::class, 'index'])
        ->middleware('permission:admin access')
        ->name('roles.index');
    Route::get('/positions', [UserController::class, 'index'])
        ->middleware('permission:view')
        ->name('positions.index');
});

// Admin dashboard
Route::middleware(['auth', 'verified'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))
            ->middleware('permission:admin access')
            ->name('dashboard');

        // Create - bisa diakses oleh admin access ATAU create
        Route::get('/users/create', [UserController::class, 'create'])
            ->middleware('role_or_permission:admin access|create')
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->middleware('role_or_permission:admin access|create')
            ->name('users.store');

        // Edit - bisa diakses oleh admin access ATAU update
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->middleware('role_or_permission:admin access|update')
            ->name('users.edit');

        Route::post('/users/{user}/update', [UserController::class, 'update'])
            ->middleware('role_or_permission:admin access|update')
            ->name('users.update');

        Route::delete('/users/{user}', [UserController::class, 'destroy'])
            ->middleware('permission:delete')
            ->name('users.destroy');
    });

// SSO
Route::get('/sso/app', [SsoController::class, 'redirectToApp'])->name('sso.app');

// Auth routes
require __DIR__ . '/auth.php';
