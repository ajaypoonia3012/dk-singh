<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\Plan;

class CheckoutController extends Controller
{
    public function checkout($id)
    {
        $plan = Plan::findOrFail($id);

        $order = Order::create([

            'user_id' => auth()->id(),

            'item_type' => 'plan',

            'item_id' => $plan->id,

            'amount' => $plan->price,

            'payment_status' => 'pending',

        ]);

        return view('checkout.index', compact('plan', 'order'));
    }
}