<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Achievement;
use Illuminate\Support\Facades\Auth;
use App\Services\AchievementService; 
class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();
    
        // The variable is now correctly named $achievementsByTier
        $achievementsByTier = Achievement::all()->groupBy('tier');
    
        $userAchievements = $user->achievements()->pluck('achievement_id')->toArray();
        $progressData = (new AchievementService())->getProgressData($user);
    
        // Pass the correctly named variable to the view
        return view('achievements.index', compact('achievementsByTier', 'userAchievements', 'progressData'));
    }
}
