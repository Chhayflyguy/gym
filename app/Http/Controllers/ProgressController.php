<?php
namespace App\Http\Controllers;
use App\Models\Exercise;
use App\Models\PersonalRecord;
use Illuminate\Support\Facades\Auth;

class ProgressController extends Controller {
    public function index() {
        // Fetch exercises that the user has actually logged PRs for
        $exercises = Exercise::whereHas('personalRecords', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderBy('name')->get();

        return view('progress.index', compact('exercises'));
    }

    public function chartData(Exercise $exercise) {
        // Get all PRs for the user and selected exercise, oldest first
        $records = PersonalRecord::where('user_id', Auth::id())
            ->where('exercise_id', $exercise->id)
            ->orderBy('log_date', 'asc')
            ->get();

        // Format the data for Chart.js
        $labels = $records->map(fn($pr) => $pr->log_date->format('M j'));
        $data = $records->map(fn($pr) => $pr->weight_kg);

        return response()->json(['labels' => $labels, 'data' => $data]);
    }
}