<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Exercise;
use App\Models\WorkoutLog;
class LeaderboardController extends Controller
{//
    public function index(Request $request) {
        $exercises = Exercise::orderBy('name')->get();
        $leaderboard = [];
    
        if ($request->filled('exercise_id')) {
            $leaderboard = WorkoutLog::select('user_id', DB::raw('MAX(weight_kg) as max_weight'))
                ->where('exercise_id', $request->exercise_id)
                ->groupBy('user_id')
                ->orderBy('max_weight', 'desc')
                ->with('user') // Eager load user data
                ->take(10)
                ->get();
        }
        return view('leaderboard.index', compact('leaderboard', 'exercises'));
    }
}
