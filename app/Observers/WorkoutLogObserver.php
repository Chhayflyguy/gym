<?php

namespace App\Observers;

use App\Models\WorkoutLog;
use App\Models\PersonalRecord;
use App\Services\AchievementService; 
class WorkoutLogObserver
{
    /**
     * Handle the WorkoutLog "created" event.
     */
    public function created(WorkoutLog $workoutLog): void {
        // Find the user's current PR for this exercise
        $currentPr = PersonalRecord::where('user_id', $workoutLog->user_id)
            ->where('exercise_id', $workoutLog->exercise_id)
            ->orderBy('weight_kg', 'desc')
            ->first();

        // If no PR exists, or if the new lift is heavier, it's a new PR
        if (!$currentPr || $workoutLog->weight_kg > $currentPr->weight_kg) {
            PersonalRecord::create([
                'user_id' => $workoutLog->user_id,
                'exercise_id' => $workoutLog->exercise_id,
                'workout_log_id' => $workoutLog->id,
                'weight_kg' => $workoutLog->weight_kg,
                'reps' => $workoutLog->reps,
                'log_date' => $workoutLog->log_date,
            ]);

            // Flash a success message to the session
            session()->flash('pr_achieved', "🏆 New Personal Record for {$workoutLog->exercise->name}: {$workoutLog->weight_kg} kg!");
        }
        (new AchievementService())->checkAndAwardAchievements($workoutLog->user);
    }

    /**
     * Handle the WorkoutLog "updated" event.
     */
    public function updated(WorkoutLog $workoutLog): void
    {
        //
    }

    /**
     * Handle the WorkoutLog "deleted" event.
     */
    public function deleted(WorkoutLog $workoutLog): void
    {
        //
    }

    /**
     * Handle the WorkoutLog "restored" event.
     */
    public function restored(WorkoutLog $workoutLog): void
    {
        //
    }

    /**
     * Handle the WorkoutLog "force deleted" event.
     */
    public function forceDeleted(WorkoutLog $workoutLog): void
    {
        //
    }
}
