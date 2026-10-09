<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\DietPlan;
use App\Models\Program;
use App\Models\Service;
use App\Models\Transformation;
use App\Models\WorkoutPlan;

class FitnessHubController extends Controller
{
    public function index()
    {
        $workouts = WorkoutPlan::where('required_access', 'public')
            ->latest()
            ->take(6)
            ->get();

        $diets = DietPlan::where('required_access', 'public')
            ->latest()
            ->take(6)
            ->get();

        $blogs = BlogPost::where('status', true)
            ->latest('published_at')
            ->take(6)
            ->get();

        $transformations = Transformation::latest()
            ->take(12)
            ->get();

        $programs = Program::latest()
            ->take(3)
            ->get();

        $services = Service::where('status', 1)
            ->latest()
            ->take(3)
            ->get();

        return view(
            'fitness-hub.index',
            compact(
                'workouts',
                'diets',
                'blogs',
                'transformations',
                'programs',
                'services'
            )
        );
    }
}
