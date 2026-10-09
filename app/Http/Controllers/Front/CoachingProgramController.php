<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Program;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Http\Request;

class CoachingProgramController extends Controller
{
    /**
     * Display the unified Coaching & Programs hub.
     * Integrates Transformation Programs, 1-on-1 Coaching Services, and All-Access Membership Plans.
     */
    public function index(Request $request)
    {
        $selectedProgram = null;
        if ($request->filled('program')) {
            $selectedProgram = Program::where('slug', $request->query('program'))->first();
        }

        $programs = Program::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $services = Service::where('status', 1)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $plans = Plan::where('status', true)
            ->orderBy('sort_order')
            ->latest()
            ->get();

        $setting = Setting::first();

        return view('coaching-programs.index', compact('programs', 'services', 'plans', 'selectedProgram', 'setting'));
    }
}
