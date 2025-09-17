<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalRecord extends Model
{
    use HasFactory;
    
    protected $fillable = ['user_id', 'exercise_id', 'workout_log_id', 'weight_kg', 'reps', 'log_date'];
    
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'log_date' => 'date', // Add this line
    ];

    public function user() { return $this->belongsTo(User::class); }
    public function exercise() { return $this->belongsTo(Exercise::class); }
    public function workoutLog() { return $this->belongsTo(WorkoutLog::class); }
}