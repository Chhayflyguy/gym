<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- Make sure this is imported

class WorkoutLogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exercises = Exercise::orderBy('name')->get();
        $logsToday = WorkoutLog::where('user_id', Auth::id())
                            ->where('log_date', now()->toDateString())
                            ->with('exercise')
                            ->get();

        return view('dashboard', [
            'exercises' => $exercises,
            'logsToday' => $logsToday
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'exercise_id' => 'required|exists:exercises,id',
            'weight_kg' => 'required|numeric|min:0',
            'reps' => 'required|integer|min:1',
            'sets' => 'required|integer|min:1',
        ]);

        for ($i = 1; $i <= $request->sets; $i++) {
            WorkoutLog::create([
                'user_id' => Auth::id(),
                'exercise_id' => $request->exercise_id,
                'log_date' => now()->toDateString(),
                'weight_kg' => $request->weight_kg,
                'reps' => $request->reps,
                'set_number' => $i,
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Workout logged!');
    }

    /**
     * Update the specified workout log in storage.
     */
    public function update(Request $request, WorkoutLog $workoutLog)
    {
        // CHANGED THIS LINE
        if (Auth::id() !== $workoutLog->user_id) {
            abort(403);
        }

        $validated = $request->validate([
            'weight_kg' => 'required|numeric|min:0',
            'reps' => 'required|integer|min:1',
        ]);

        $workoutLog->update($validated);

        return redirect()->route('dashboard')->with('success', 'Set updated successfully!');
    }

    /**
     * Remove the specified workout log from storage.
     */
    public function destroy(WorkoutLog $workoutLog)
    {
        // CHANGED THIS LINE
        if (Auth::id() !== $workoutLog->user_id) {
            abort(403);
        }

        $workoutLog->delete();

        return redirect()->route('dashboard')->with('success', 'Set deleted.');
    }
}