<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::where('status', true)
           ->latest()
            ->get();

        return view('plans.index', compact('plans'));
    }
}