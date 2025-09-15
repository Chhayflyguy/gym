<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ExerciseController;
Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/dashboard', [WorkoutLogController::class, 'index'])->name('dashboard');
    Route::post('/workout-logs', [WorkoutLogController::class, 'store'])->name('workout-logs.store');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    Route::resource('schedules', ScheduleController::class);

    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');

    Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');

});

require __DIR__.'/auth.php';
