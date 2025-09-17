<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Achievement extends Model
{
    protected $fillable = ['name', 'description', 'icon', 'tier', 'type', 'value'];
public function users() {
    return $this->belongsToMany(User::class, 'user_achievements')->withTimestamps('unlocked_at');
}
}
