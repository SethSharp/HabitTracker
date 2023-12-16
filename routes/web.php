<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Pages\ShowHelpController;
use App\Http\Controllers\Habits\EditHabitController;
use App\Http\Controllers\Habits\ShowHabitsController;
use App\Http\Controllers\Habits\StoreHabitController;
use App\Http\Controllers\Habits\CreateHabitController;
use App\Http\Controllers\Habits\DeleteHabitController;
use App\Http\Controllers\Habits\UpdateHabitController;
use App\Http\Controllers\Pages\ShowCalendarController;
use App\Http\Controllers\Pages\ShowDashboardController;
use App\Http\Controllers\Profile\EditProfileController;
use App\Http\Controllers\Profile\DeleteProfileController;
use App\Http\Controllers\Profile\UpdateProfileController;
use App\Http\Controllers\Profile\UpdateEmailPreferencesController;
use App\Http\Controllers\HabitSchedule\CancelHabitScheduleController;
use App\Http\Controllers\HabitSchedule\CompleteHabitScheduleController;
use App\Http\Controllers\HabitSchedule\GetScheduledHabitsForDayController;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/{week?}', ShowDashboardController::class)->name('dashboard');
    Route::get('/habits', ShowHabitsController::class)->name('habit');
    Route::get('/help', ShowHelpController::class)->name('help');
    Route::get('/calendar/{month?}', ShowCalendarController::class)->name('calendar');

    Route::prefix('habits')->name('habit.')->group(function () {
        Route::get('/create', CreateHabitController::class)->name('create');
        Route::get('/edit/{habit}', EditHabitController::class)->name('edit');
        Route::post('/{habit}', UpdateHabitController::class)->name('update');
        Route::delete('/delete/{habit}', DeleteHabitController::class)->name('delete');
        Route::post('/', StoreHabitController::class)->name('store');
    });

    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('/day/{date}', GetScheduledHabitsForDayController::class)->name('day');
        Route::post('/complete/{habitSchedule}', CompleteHabitScheduleController::class)->name('complete');
        Route::post('/cancel/{habitSchedule}', CancelHabitScheduleController::class)->name('cancel');
    });

    Route::name('profile.')->group(function () {
        Route::get('/profile', EditProfileController::class)->name('edit');
        Route::patch('/profile', UpdateProfileController::class)->name('update');
        Route::delete('/profile', DeleteProfileController::class)->name('destroy');
    });

    Route::patch('/email-preferences', UpdateEmailPreferencesController::class)->name('email-preferences.update');
});

require __DIR__ . '/auth.php';
