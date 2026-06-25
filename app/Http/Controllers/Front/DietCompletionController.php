<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\DietCompletion;
use App\Models\DietPlan;

class DietCompletionController extends Controller
{
    public function store($id)
    {
        $diet = DietPlan::findOrFail($id);

        $exists = DietCompletion::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'diet_plan_id',
            $diet->id
        )
        ->exists();

        if (! $exists) {

            DietCompletion::create([

                'user_id' => auth()->id(),

                'diet_plan_id' => $diet->id,

                'completed_at' => now(),

            ]);
        }

        return back()->with(
            'success',
            'Diet marked as completed.'
        );
    }
}