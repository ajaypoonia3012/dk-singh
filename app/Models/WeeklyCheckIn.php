<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WeeklyCheckIn extends Model
{
    protected $fillable = [

        'user_id',
        'weight',
        'waist',
        'energy_level',
        'mood',
        'sleep_hours',
        'notes',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}