<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Exercise::create(['name' => 'Bench Press', 'body_part' => 'Chest']);
        Exercise::create(['name' => 'Squat', 'body_part' => 'Legs']);
        Exercise::create(['name' => 'Deadlift', 'body_part' => 'Back']);
        Exercise::create(['name' => 'Overhead Press', 'body_part' => 'Shoulders']);
        Exercise::create(['name' => 'Lat Pulldown', 'body_part' => 'Back']);
    }
}
