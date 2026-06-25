<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\ProgressLog;

class MemberTransformationController extends Controller
{
    public function index()
    {
        $logs = ProgressLog::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        $latest = $logs->first();

        $oldest = $logs->last();

        $weightLost = 0;
        $bmiImprovement = 0;

        if ($latest && $oldest) {

            $weightLost =
                $oldest->weight -
                $latest->weight;

            $bmiImprovement =
                $oldest->bmi -
                $latest->bmi;
        }

        return view(
            'member.transformations.index',
            compact(
                'logs',
                'latest',
                'oldest',
                'weightLost',
                'bmiImprovement'
            )
        );
    }
}