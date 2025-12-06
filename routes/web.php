<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Overwatch\AdminAuthController;
use App\Http\Controllers\Overwatch\OverwatchDashboardController;
use App\Http\Controllers\Overwatch\AdminUserController;

Route::get('/', function () {
    return view('landing');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback']);

  //****************//
 //      ADMIN     //
//****************//
Route::prefix('overwatch')->name('overwatch.')->group(function () {
    // Show admin login
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected admin routes
    Route::middleware(['auth', 'is_admin'])->group(function () {
        Route::get('dashboard', [OverwatchDashboardController::class, 'index'])->name('dashboard');

        // Users (privacy-friendly: only metadata + actions)
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/toggle', [AdminUserController::class, 'toggleActive'])->name('users.toggle');
        Route::post('users/{user}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset_password');
    });
});




require __DIR__.'/auth.php';
