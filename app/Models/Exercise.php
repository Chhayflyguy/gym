<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'body_part'];

    /**
     * An exercise can have many workout logs.
     */
    public function workoutLogs()
    {
        return $this->hasMany(WorkoutLog::class);
    }

    /**
     * An exercise can have many personal records.
     */
    public function personalRecords()
    {
        return $this->hasMany(PersonalRecord::class);
    }
}