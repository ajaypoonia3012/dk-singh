<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\WorkoutPlan;

class WorkoutPlanController extends Controller
{
    public function index()
{

    if (!auth()->check()) {

        return redirect('/login');
    }

        if (!auth()->user()->hasBasicAccess()) {

            return redirect('/plans')
                ->with('error', 'Please purchase a membership.');
        }

        $workouts = WorkoutPlan::latest()->get();

        return view('workout-plans.index', compact('workouts'));
    }

    public function show($id)
    {
        if (!auth()->check()) {

            return redirect('/login');
        }

        if (!auth()->user()->hasBasicAccess()) {

            return redirect('/plans')
                ->with('error', 'Please purchase a membership.');
        }

        $workout = WorkoutPlan::findOrFail($id);

        return view('workout-plans.show', compact('workout'));
    }
}