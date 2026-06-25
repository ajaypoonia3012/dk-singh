<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\WeeklyCheckIn;
use Illuminate\Http\Request;

class WeeklyCheckInController extends Controller
{
    public function index()
    {
        $checkIns = WeeklyCheckIn::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return view(
            'member.check-ins.index',
            compact('checkIns')
        );
    }

    public function store(Request $request)
    {
        $request->validate([

            'weight' => 'nullable|numeric',
            'waist' => 'nullable|numeric',
            'energy_level' => 'nullable|integer|min:1|max:10',
            'mood' => 'nullable|integer|min:1|max:10',
            'sleep_hours' => 'nullable|numeric',
            'notes' => 'nullable|string',

        ]);

        WeeklyCheckIn::create([

            'user_id' => auth()->id(),

            'weight' => $request->weight,

            'waist' => $request->waist,

            'energy_level' => $request->energy_level,

            'mood' => $request->mood,

            'sleep_hours' => $request->sleep_hours,

            'notes' => $request->notes,

        ]);

       return redirect()
    ->route('member.progress')
    ->with(
        'success',
        'Weekly check-in submitted successfully.'
    );
    }
}