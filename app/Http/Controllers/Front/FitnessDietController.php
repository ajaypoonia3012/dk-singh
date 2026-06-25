<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;

class FitnessDietController extends Controller
{
    public function index()
    {
        $diets = DietPlan::where('required_access', 'public')
            ->latest()
            ->paginate(12);

        return view(
            'fitness-hub.diets.index',
            compact('diets')
        );
    }

    public function show($slug)
    {
        $diet = DietPlan::where('slug', $slug)
            ->where('required_access', 'public')
            ->firstOrFail();

        return view(
            'fitness-hub.diets.show',
            compact('diet')
        );
    }
}