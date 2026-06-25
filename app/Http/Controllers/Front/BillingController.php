<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;

class BillingController extends Controller
{
    public function index()
    {
        $orders = Order::where(
            'user_id',
            auth()->id()
        )
        ->latest()
        ->get();

        return view(
            'member.billing',
            compact('orders')
        );
    }
}