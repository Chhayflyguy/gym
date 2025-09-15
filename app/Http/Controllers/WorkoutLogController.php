<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\WorkoutLog;
use Illuminate\Support\Facades\Auth;
class WorkoutLogController extends Controller
{
    public function index() {
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
    
    public function store(Request $request) {
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
}
