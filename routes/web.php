<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
    ]);
});

Route::middleware('auth')->name('habit.')->group(function () {
    Route::get('/habits/create', \App\Http\Controllers\Habits\CreateHabitController::class)->name('create');
});


Route::get('/dashboard', \App\Http\Controllers\Pages\ShowDashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/habits', \App\Http\Controllers\Habits\ShowHabitsController::class)->middleware(['auth', 'verified'])->name('habits');
Route::get('/competition', \App\Http\Controllers\Pages\ShowCompetitionController::class)->middleware(['auth', 'verified'])->name('competition');

Route::middleware('auth')->group(function () {
    Route::get('/profile', \App\Http\Controllers\Profile\EditProfileController::class)->name('profile.edit');
    Route::patch('/profile', \App\Http\Controllers\Profile\UpdateProfileController::class)->name('profile.update');
    Route::delete('/profile', \App\Http\Controllers\Profile\DeleteProfileController::class)->name('profile.destroy');
});

require __DIR__.'/auth.php';
