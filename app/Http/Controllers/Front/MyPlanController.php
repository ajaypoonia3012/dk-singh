<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;

class MyPlanController extends Controller
{
    public function index()
{
    $membership = auth()->user()->activeMembership;

    $daysRemaining = null;

    if ($membership) {

        $daysRemaining = (int) now()->diffInDays(
            $membership->expires_at,
            false
        );
    }

    $orders = \App\Models\Order::where(
        'user_id',
        auth()->id()
    )
    ->latest()
    ->take(5)
    ->get();

    return view(
        'member.my-plan',
        compact(
            'membership',
            'daysRemaining',
            'orders'
        )
    );
}
}