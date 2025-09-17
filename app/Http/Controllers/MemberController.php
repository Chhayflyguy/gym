<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    /**
     * Display a list of all members.
     */
    public function index()
    {
        $users = User::latest()->paginate(12); // Fetch all users, 12 per page
        return view('members.index', compact('users'));
    }

    /**
     * Display a specific member's profile and workout history.
     */
    public function show(User $user)
    {
        // Fetch the specified user's workout logs and group them by date
        $workouts = $user->workoutLogs()
            ->with('exercise')
            ->latest('log_date')
            ->get();

        $workoutsByDate = $workouts->groupBy(function ($log) {
            return $log->log_date->format('Y-m-d');
        });

        return view('members.show', compact('user', 'workoutsByDate'));
    }
}