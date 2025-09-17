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

// Resource dashboard (user, role, position) untuk user biasa
Route::middleware(['auth', 'verified'])->prefix('dashboard')->name('dashboard.')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/roles', [UserController::class, 'index'])->name('roles.index');
    Route::get('/positions', [UserController::class, 'index'])->name('positions.index');
});

// Admin dashboard dan fitur admin (hanya untuk admin)
Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        // Tambahkan route admin lain di sini
    });

// SSO
Route::get('/sso/app', [SsoController::class, 'redirectToApp'])->name('sso.app');

// Auth routes
require __DIR__ . '/auth.php';