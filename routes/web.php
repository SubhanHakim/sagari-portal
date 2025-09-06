<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SsoController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'auth.login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'verified', 'role:admin'])
    ->prefix('admin')->name('admin.')
    ->group(function () {
        Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

        // contoh: halaman create user hanya admin
        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');
        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');
    });


Route::get('/sso/app', [SsoController::class, 'redirectToApp'])->name('sso.app');

require __DIR__ . '/auth.php';
