<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SsoController;
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

Route::get('/sso/app', [SsoController::class, 'redirectToApp'])->name('sso.app');

require __DIR__.'/auth.php';
