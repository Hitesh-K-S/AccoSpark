<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\Overwatch\AdminAuthController;
use App\Http\Controllers\Overwatch\OverwatchDashboardController;
use App\Http\Controllers\Overwatch\AdminUserController;
use App\Http\Controllers\Overwatch\AIPersonaController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\GoogleCalendarController;
use App\Http\Controllers\DailyCheckinController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('landing');
});



Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware('auth')->group(function () {

     // User Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Persona selection
    Route::post('/profile/persona', [ProfileController::class, 'updatePersona'])
        ->name('profile.persona.update');

    // Goal setting
    Route::get('/goals', [GoalController::class, 'index'])->name('goals.index');
    Route::get('/goals/create', [GoalController::class, 'create'])->name('goals.create');
    Route::post('/goals', [GoalController::class, 'store'])->name('goals.store');
    Route::get('/goals/{goal}', [GoalController::class, 'show'])->name('goals.show');
    Route::get('/goals/{goal}/preview', [GoalController::class, 'preview'])->name('goals.preview');
    Route::post('/goals/{goal}/confirm', [GoalController::class, 'confirm'])->name('goals.confirm');
    Route::delete('/goals/{goal}', [GoalController::class, 'destroy'])->name('goals.destroy');
    Route::post('/goals/{goal}/pause', [GoalController::class, 'pause'])->name('goals.pause');
    Route::post('/goals/{goal}/resume', [GoalController::class, 'resume'])->name('goals.resume');

    // Calendar connect
    Route::get('/calendar/connect', [GoogleCalendarController::class, 'connect'])
        ->name('calendar.connect');

    // OAuth callback
    Route::get('/calendar/callback', [GoogleCalendarController::class, 'callback'])
        ->name('calendar.callback');

    // Disconnect calendar
    Route::post('/calendar/disconnect', [GoogleCalendarController::class, 'disconnect'])
        ->name('calendar.disconnect');

    //Daily Checkins
    Route::get('/checkin', [DailyCheckinController::class, 'create'])
        ->name('checkin.create');

    Route::post('/checkin', [DailyCheckinController::class, 'store'])
        ->name('checkin.store');

    Route::get('/checkins/history', [DailyCheckinController::class, 'history'])
        ->name('checkins.history');

    Route::post('/tasks/{task}/complete', [TaskController::class, 'complete'])
        ->name('tasks.complete');


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
        // Persona Manager
        Route::get('dashboard', [OverwatchDashboardController::class, 'index'])->name('dashboard');

        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('users/{user}/toggle-ban', [AdminUserController::class, 'toggleBan'])->name('users.toggleBan');
    
         // AI Persona Manager
        Route::get('personas', [AIPersonaController::class, 'index'])->name('personas.index');

        Route::get('personas/create', [AIPersonaController::class, 'create'])->name('personas.create');
        Route::post('personas', [AIPersonaController::class, 'store'])->name('personas.store');

        Route::get('personas/{persona}/edit', [AIPersonaController::class, 'edit'])->name('personas.edit');
        Route::put('personas/{persona}', [AIPersonaController::class, 'update'])->name('personas.update');

        Route::post('personas/{persona}/toggle', [AIPersonaController::class, 'toggle'])->name('personas.toggle');

        Route::delete('personas/{persona}', [AIPersonaController::class, 'destroy'])->name('personas.destroy');
    
    });
});




require __DIR__.'/auth.php';
