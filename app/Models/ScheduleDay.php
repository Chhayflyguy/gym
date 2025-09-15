<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @property int $id
 * @property int $schedule_id
 * @property string $day_of_week
 * @property string|null $body_part
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Schedule $schedule
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereBodyPart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereDayOfWeek($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereScheduleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ScheduleDay whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class ScheduleDay extends Model {
    use HasFactory;
    protected $fillable = ['schedule_id', 'day_of_week', 'body_part'];
    public function schedule() { return $this->belongsTo(Schedule::class); }
}