<?php

use Illuminate\Support\Facades\Route;

Route::get('/', \App\Http\Controllers\WelcomeController::class)->name('welcome');

Route::middleware('auth')->name('habit.')->group(function () {
    Route::get('/habits/create', \App\Http\Controllers\Habits\CreateHabitController::class)->name('create');
    Route::get('/habits/edit/{habit}', \App\Http\Controllers\Habits\EditHabitController::class)->name('edit');
    Route::post('habits/{habit}', \App\Http\Controllers\Habits\UpdateHabitController::class)->name('update');
    Route::delete('habits/delete/{habit}', \App\Http\Controllers\Habits\DeleteHabitController::class)->name('delete');
    Route::post('habits', \App\Http\Controllers\Habits\StoreHabitController::class)->name('store');
});

Route::middleware(['auth', 'verified'])->name('schedule.')->group(function () {
    Route::get('schedule/day/{date}', \App\Http\Controllers\HabitSchedule\ScheduledHabitsForDay::class)->name('day');
    Route::post('schedule/complete/{habitSchedule}', \App\Http\Controllers\HabitSchedule\Complete::class)->name('complete');
    Route::post('schedule/uncomplete/{habitSchedule}', \App\Http\Controllers\HabitSchedule\UnComplete::class)->name('uncomplete');
    Route::post('schedule/cancel/{habitSchedule}', \App\Http\Controllers\HabitSchedule\Cancel::class)->name('cancel');
});

// Tab group
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard/{week?}', \App\Http\Controllers\Pages\ShowDashboardController::class)->name('dashboard');
    Route::get('/habits', \App\Http\Controllers\Habits\ShowHabitsController::class)->name('habit');
    Route::get('/help', \App\Http\Controllers\Pages\ShowHelpController::class)->name('help');
    Route::get('/calendar/{month?}', \App\Http\Controllers\Pages\ShowCalendarController::class)->name('calendar');
});

Route::middleware('auth')->name('profile.')->group(function () {
    Route::get('/profile', \App\Http\Controllers\Profile\EditProfileController::class)->name('edit');
    Route::patch('/profile', \App\Http\Controllers\Profile\UpdateProfileController::class)->name('update');
    Route::delete('/profile', \App\Http\Controllers\Profile\DeleteProfileController::class)->name('destroy');
});

Route::middleware('auth')->name('email-preferences.')->group(function () {
    Route::patch('/email-preferences', \App\Http\Controllers\Profile\UpdateEmailPreferencesController::class)->name('update');
});

require __DIR__ . '/auth.php';
