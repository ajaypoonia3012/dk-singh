<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ActionPlan;

class ActionPlanController extends Controller
{
    public function index()
    {
        $actionPlans = ActionPlan::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        $completed = $actionPlans
            ->where('is_completed', true)
            ->count();

        $total = $actionPlans->count();

        return view(
            'member.action-plan.index',
            compact(
                'actionPlans',
                'completed',
                'total'
            )
        );
    }

    public function complete(ActionPlan $actionPlan)
    {
        if ($actionPlan->user_id !== auth()->id()) {
            abort(403);
        }

        $actionPlan->update([

            'is_completed' => true,

            'completed_at' => now(),

        ]);

        return back()->with(
            'success',
            'Task completed successfully.'
        );
    }
}