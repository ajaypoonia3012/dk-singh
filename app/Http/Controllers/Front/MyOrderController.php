<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Order;

class MyOrderController extends Controller
{
    public function index()
    {
        $orders = Order::where(
            'user_id',
            auth()->id()
        )
        ->where(
            'item_type',
            'product'
        )
        ->latest()
        ->get();

        return view(
            'account.orders',
            compact('orders')
        );
    }

    public function show(Order $order)
    {
        if ($order->user_id !== auth()->id()) {
            abort(403);
        }

        $order->load([
            'shipment.events',
            'shipment.courierProvider',
        ]);

        return view(
            'account.order-show',
            compact('order')
        );
    }
}