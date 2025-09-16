<?php

namespace App\Http\Controllers;

use App\Models\Exercise; // Import the Exercise model
use App\Models\WorkoutLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaderboardController extends Controller
{
    public function index(Request $request)
    {
        // 1. Fetch all exercises for the filter dropdown
        $exercises = Exercise::orderBy('name')->get();

        // Define valid columns to rank by
        $validRankings = ['total_volume_kg', 'total_reps', 'total_sets'];
        
        // Get the user's selected ranking, or default to 'total_volume_kg'
        $rankBy = $request->input('rank_by');
        if (!in_array($rankBy, $validRankings)) {
            $rankBy = 'total_volume_kg';
        }
        
        // Start building the query
        $query = WorkoutLog::select(
            'user_id',
            DB::raw('SUM(weight_kg * reps) as total_volume_kg'),
            DB::raw('SUM(reps) as total_reps'),
            DB::raw('COUNT(id) as total_sets')
        );

        // 2. Conditionally filter by a specific exercise if one is selected
        $query->when($request->filled('exercise_id'), function ($q) use ($request) {
            return $q->where('exercise_id', $request->exercise_id);
        });

        // Continue building the rest of the query
        $leaderboard = $query->with('user')
            ->groupBy('user_id')
            ->orderBy($rankBy, 'desc')
            ->paginate(15);

        // 3. Pass the exercises list to the view
        return view('leaderboard.index', compact('leaderboard', 'rankBy', 'exercises'));
    }
}