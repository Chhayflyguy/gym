<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\Achievement;

class AchievementSeeder extends Seeder {
    public function run(): void {
        Achievement::firstOrCreate(['name' => 'Consistent Start'], [
            'description' => 'Complete a workout on 5 different days.',
            'icon' => '🗓️',
            'tier' => 'Bronze', // Add tier
            'type' => 'consistency',
            'value' => 5,
        ]);
        Achievement::firstOrCreate(['name' => 'Marathon Lifter'], [
            'description' => 'Lift over 10,000kg total volume in a single workout.',
            'icon' => '🏋️‍♂️',
            'tier' => 'Silver', // Add tier
            'type' => 'total_volume_workout',
            'value' => 10000,
        ]);
        Achievement::firstOrCreate(['name' => 'Bench Press: 100kg Club'], [
            'description' => 'Lift 100kg or more in the Bench Press.',
            'icon' => '🏆',
            'tier' => 'Gold', // Add tier
            'type' => 'pr_weight_bench_press',
            'value' => 100,
        ]);
    }
}