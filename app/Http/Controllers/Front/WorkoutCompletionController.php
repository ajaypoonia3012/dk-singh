<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\WorkoutCompletion;
use App\Models\WorkoutPlan;

class WorkoutCompletionController extends Controller
{
    public function store($id)
    {
        $workout = WorkoutPlan::findOrFail($id);

        $exists = WorkoutCompletion::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'workout_plan_id',
            $workout->id
        )
        ->exists();

        if (! $exists) {

            WorkoutCompletion::create([

                'user_id' => auth()->id(),

                'workout_plan_id' => $workout->id,

                'completed_at' => now(),

            ]);
        }

        return back()->with(
            'success',
            'Workout marked as completed.'
        );
    }
}