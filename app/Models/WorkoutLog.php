<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $exercise_id
 * @property string $log_date
 * @property string $weight_kg
 * @property int $reps
 * @property int $set_number
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Exercise $exercise
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereExerciseId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereLogDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereReps($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereSetNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|WorkoutLog whereWeightKg($value)
 * @mixin \Eloquent
 */
class WorkoutLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'exercise_id',
        'log_date',
        'weight_kg',
        'reps',
        'set_number',
    ];

    public function user() { 
        return $this->belongsTo(User::class); 
    }

    public function exercise() { 
        return $this->belongsTo(Exercise::class); 
    }
}
