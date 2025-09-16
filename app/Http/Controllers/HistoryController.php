<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index()
    {
        // 1. Fetch all logs for the logged-in user, newest first
        $workouts = Auth::user()->workoutLogs()
            ->with('exercise') // Eager load exercise details to prevent extra queries
            ->latest('log_date') // Order by date, descending
            ->get();

        // 2. Group the collection of logs by the 'log_date' field
        $workoutsByDate = $workouts->groupBy(function ($log) {
            return $log->log_date->format('Y-m-d');
        });

        // 3. Pass the grouped data to the view
        return view('history.index', compact('workoutsByDate'));
    }
}