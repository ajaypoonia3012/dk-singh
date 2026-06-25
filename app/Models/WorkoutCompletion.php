<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkoutCompletion extends Model
{
    protected $fillable = [

        'user_id',
        'workout_plan_id',
        'completed_at',

    ];

    protected $casts = [

        'completed_at' => 'datetime',

    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function workoutPlan()
    {
        return $this->belongsTo(WorkoutPlan::class);
    }
}