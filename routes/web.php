<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\WelcomeController::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/{week?}', \App\Http\Controllers\Pages\ShowDashboardController::class)->name('dashboard');
    Route::get('/habits', \App\Http\Controllers\Habits\ShowHabitsController::class)->name('habit');
    Route::get('/help', \App\Http\Controllers\Pages\ShowHelpController::class)->name('help');
    Route::get('/calendar/{month?}', \App\Http\Controllers\Pages\ShowCalendarController::class)->name('calendar');

    Route::prefix('habits')->name('habit.')->group(function () {
        Route::get('/create', \App\Http\Controllers\Habits\CreateHabitController::class)->name('create');
        Route::get('/edit/{habit}', \App\Http\Controllers\Habits\EditHabitController::class)->name('edit');
        Route::post('/{habit}', \App\Http\Controllers\Habits\UpdateHabitController::class)->name('update');
        Route::delete('/delete/{habit}', \App\Http\Controllers\Habits\DeleteHabitController::class)->name('delete');
        Route::post('/', \App\Http\Controllers\Habits\StoreHabitController::class)->name('store');
    });

    Route::prefix('schedule')->name('schedule.')->group(function () {
        Route::get('schedule/day/{date}', \App\Http\Controllers\HabitSchedule\GetScheduledHabitsForDayController::class)->name('day');
        Route::post('schedule/complete/{habitSchedule}', \App\Http\Controllers\HabitSchedule\CompleteHabitScheduleController::class)->name('complete');
        Route::post('schedule/cancel/{habitSchedule}', \App\Http\Controllers\HabitSchedule\CancelHabitScheduleController::class)->name('cancel');
    });

    Route::name('profile.')->group(function () {
        Route::get('/profile', \App\Http\Controllers\Profile\EditProfileController::class)->name('edit');
        Route::patch('/profile', \App\Http\Controllers\Profile\UpdateProfileController::class)->name('update');
        Route::delete('/profile', \App\Http\Controllers\Profile\DeleteProfileController::class)->name('destroy');
    });

    Route::patch('/email-preferences', \App\Http\Controllers\Profile\UpdateEmailPreferencesController::class)->name('email-preferences.update');
});

require __DIR__ . '/auth.php';
