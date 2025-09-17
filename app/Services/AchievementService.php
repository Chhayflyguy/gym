<?php
namespace App\Services;
use App\Models\User;
use App\Models\Achievement;
use App\Models\WorkoutLog;
use Illuminate\Support\Facades\DB;

class AchievementService {
    public function checkAndAwardAchievements(User $user) {
        $achievements = Achievement::all();
        $unlockedAchievements = $user->achievements()->pluck('achievement_id')->toArray();

        foreach ($achievements as $achievement) {
            if (!in_array($achievement->id, $unlockedAchievements)) {
                $unlocked = false;
                switch ($achievement->type) {
                    case 'pr_weight_bench_press':
                        $unlocked = $this->checkPrWeight($user, 'Bench Press', $achievement->value);
                        break;
                    case 'total_volume_workout':
                        $unlocked = $this->checkWorkoutVolume($user, $achievement->value);
                        break;
                    case 'consistency':
                        $unlocked = $this->checkConsistency($user, $achievement->value);
                        break;
                }
                if ($unlocked) {
                    $user->achievements()->attach($achievement->id);
                    session()->flash('achievement_unlocked', "🏆 Achievement Unlocked: {$achievement->name}");
                }
            }
        }
    }
    private function checkPrWeight(User $user, string $exerciseName, int $requiredWeight) {
        return $user->personalRecords()
            ->whereHas('exercise', fn($q) => $q->where('name', $exerciseName))
            ->where('weight_kg', '>=', $requiredWeight)
            ->exists();
    }
    private function checkWorkoutVolume(User $user, int $requiredVolume) {
        $maxVolume = $user->workoutLogs()
            ->select('log_date', DB::raw('SUM(weight_kg * reps) as daily_volume'))
            ->groupBy('log_date')
            ->get()
            ->max('daily_volume');
        return $maxVolume >= $requiredVolume;
    }
    private function checkConsistency(User $user, int $requiredDays) {
        return $user->workoutLogs()->distinct('log_date')->count('log_date') >= $requiredDays;
    }

    public function getProgressData(User $user) {
        $progress = [];
        $achievements = Achievement::all();
        $unlockedIds = $user->achievements()->pluck('achievement_id')->toArray();

        foreach ($achievements as $achievement) {
            if (in_array($achievement->id, $unlockedIds)) continue; // Skip already unlocked

            $currentValue = 0;
            switch ($achievement->type) {
                case 'pr_weight_bench_press':
                    $pr = $user->personalRecords()
                        ->whereHas('exercise', fn($q) => $q->where('name', 'Bench Press'))
                        ->max('weight_kg');
                    $currentValue = $pr ?? 0;
                    break;
                case 'total_volume_workout':
                    $maxVolume = $user->workoutLogs()
                        ->select('log_date', DB::raw('SUM(weight_kg * reps) as daily_volume'))
                        ->groupBy('log_date')->get()->max('daily_volume');
                    $currentValue = $maxVolume ?? 0;
                    break;
                case 'consistency':
                    $currentValue = $user->workoutLogs()->distinct('log_date')->count('log_date');
                    break;
            }

            $progress[$achievement->id] = [
                'current' => $currentValue,
                'goal' => $achievement->value,
                'percent' => $achievement->value > 0 ? ($currentValue / $achievement->value) * 100 : 0,
            ];
        }
        return $progress;
    }
}