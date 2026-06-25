<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;

class FitnessWorkoutController extends Controller
{
    public function index()
    {
        $workouts = WorkoutPlan::where('required_access', 'public')
            ->latest()
            ->paginate(12);

        return view(
            'fitness-hub.workouts.index',
            compact('workouts')
        );
    }

    public function show($slug)
    {
        $workout = WorkoutPlan::where('slug', $slug)
            ->where('required_access', 'public')
            ->firstOrFail();

        return view(
            'fitness-hub.workouts.show',
            compact('workout')
        );
    }
}