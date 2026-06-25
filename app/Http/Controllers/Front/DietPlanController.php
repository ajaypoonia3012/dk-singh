<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DietPlan;

class DietPlanController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {

            return redirect('/login');
        }

        if (!auth()->user()->hasProAccess()) {

            return redirect('/plans')
                ->with('error', 'Pro or Elite membership required.');
        }

        $dietPlans = DietPlan::latest()->get();

        return view('diet-plans.index', compact('dietPlans'));
    }

    public function show($id)
    {
        if (!auth()->check()) {

            return redirect('/login');
        }

        if (!auth()->user()->hasProAccess()) {

            return redirect('/plans')
                ->with('error', 'Pro or Elite membership required.');
        }

        $dietPlan = DietPlan::findOrFail($id);

        return view('diet-plans.show', compact('dietPlan'));
    }
}