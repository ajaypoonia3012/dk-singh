<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Program;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index(Request $request)
    {
        $selectedProgram = null;
        if ($request->filled('program')) {
            $selectedProgram = Program::where('slug', $request->query('program'))->first();
        }

        $plans = Plan::where('status', true)
            ->latest()
            ->get();

        return view('plans.index', compact('plans', 'selectedProgram'));
    }
}
