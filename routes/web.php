<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\ProgressController;
use App\Http\Controllers\AchievementController;
use App\Http\Controllers\MemberController; 
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
    Route::put('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'update'])->name('workout-logs.update');
    Route::delete('/workout-logs/{workoutLog}', [WorkoutLogController::class, 'destroy'])->name('workout-logs.destroy');

    Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
    Route::post('/schedule', [ScheduleController::class, 'store'])->name('schedule.store');

    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard.index');

    Route::resource('schedules', ScheduleController::class);

    Route::get('/schedules/{schedule}', [ScheduleController::class, 'show'])->name('schedules.show');

    Route::post('/exercises', [ExerciseController::class, 'store'])->name('exercises.store');

    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    Route::get('/progress', [ProgressController::class, 'index'])->name('progress.index');
    Route::get('/progress/chart-data/{exercise}', [ProgressController::class, 'chartData'])->name('progress.chart-data');

    Route::get('/achievements', [AchievementController::class, 'index'])->name('achievements.index');
    
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::get('/members/{user}', [MemberController::class, 'show'])->name('members.show');

});

require __DIR__.'/auth.php';
