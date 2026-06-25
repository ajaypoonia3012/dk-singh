<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Membership;
use App\Models\WeeklyCheckIn;
use App\Models\CoachNote;
use App\Models\WorkoutCompletion;
use App\Models\WorkoutPlan;
use App\Models\DietCompletion;
use App\Models\DietPlan;
use Barryvdh\DomPDF\Facade\Pdf;

class ProgressReportController extends Controller
{
    public function download()
    {
        $user = auth()->user();

        $membership = Membership::where(
            'user_id',
            $user->id
        )->latest()->first();

        $firstCheckIn = WeeklyCheckIn::where(
            'user_id',
            $user->id
        )
        ->oldest()
        ->first();

        $latestCheckIn = WeeklyCheckIn::where(
            'user_id',
            $user->id
        )
        ->latest()
        ->first();

        $checkIns = WeeklyCheckIn::where(
            'user_id',
            $user->id
        )
        ->orderBy('created_at')
        ->get();

        $latestCoachNote = CoachNote::where(
            'user_id',
            $user->id
        )
        ->where(
            'is_visible',
            true
        )
        ->latest()
        ->first();

        $completedWorkouts = WorkoutCompletion::where(
            'user_id',
            $user->id
        )->count();

        $totalWorkouts = WorkoutPlan::count();

        $workoutCompliance =
            $totalWorkouts > 0
                ? round(
                    ($completedWorkouts / $totalWorkouts) * 100
                )
                : 0;

        $completedDiets = DietCompletion::where(
            'user_id',
            $user->id
        )->count();

        $totalDiets = DietPlan::count();

        $dietCompliance =
            $totalDiets > 0
                ? round(
                    ($completedDiets / $totalDiets) * 100
                )
                : 0;

        $weightLost = 0;

        if ($firstCheckIn && $latestCheckIn) {

            $weightLost =
                $firstCheckIn->weight -
                $latestCheckIn->weight;
        }

        $journeyDays = 0;

        if ($firstCheckIn && $latestCheckIn) {

            $journeyDays = max(
                1,
                $firstCheckIn->created_at
                    ->diffInDays(
                        $latestCheckIn->created_at
                    )
            );
        }

        $pdf = Pdf::loadView(
            'member.reports.progress-report',
            compact(
                'user',
                'membership',
                'firstCheckIn',
                'latestCheckIn',
                'checkIns',
                'latestCoachNote',
                'workoutCompliance',
                'dietCompliance',
                'weightLost',
                'journeyDays'
            )
        );

        return $pdf->download(
            'progress-report.pdf'
        );
    }
}