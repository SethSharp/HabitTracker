<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Controllers\WelcomeController::class)->name('welcome');

Route::middleware('auth')->name('habit.')->group(function () {
    Route::get('/habits/create', \App\Http\Controllers\Habits\CreateHabitController::class)->name('create');
    Route::get('/habits/edit/{habit}', \App\Http\Controllers\Habits\EditHabitController::class)->name('edit');
    Route::post('/habits/restore/{habit}', \App\Http\Controllers\Habits\RestoreHabitController::class)->name('restore');
    Route::post('habits/{habit}', \App\Http\Controllers\Habits\UpdateHabitController::class)->name('update');
    Route::delete('habits/delete/{habit}', \App\Http\Controllers\Habits\DeleteHabitController::class)->name('delete');
    Route::post('habits', \App\Http\Controllers\Habits\StoreHabitController::class)->name('store');
});

Route::middleware('auth')->name('schedule.')->group(function () {
    Route::post('schedule/habits', \App\Http\Controllers\ScheduleHabit\UpdateHabitScheduleController::class)->name('update');
});


Route::get('/dashboard', \App\Http\Controllers\Pages\ShowDashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/habits', \App\Http\Controllers\Habits\ViewHabitsController::class)->middleware(['auth', 'verified'])->name('habits');
Route::get('/competition', \App\Http\Controllers\Pages\ShowCompetitionController::class)->middleware(['auth', 'verified'])->name('competition');

Route::middleware('auth')->name('profile.')->group(function () {
    Route::get('/profile', \App\Http\Controllers\Profile\EditProfileController::class)->name('edit');
    Route::patch('/profile', \App\Http\Controllers\Profile\UpdateProfileController::class)->name('update');
    Route::delete('/profile', \App\Http\Controllers\Profile\DeleteProfileController::class)->name('destroy');
});

Route::middleware('auth')->name('email-preferences.')->group(function () {
    Route::patch('/email-preferences', \App\Http\Controllers\Profile\UpdateEmailPreferencesController::class)->name('update');
});

require __DIR__ . '/auth.php';
