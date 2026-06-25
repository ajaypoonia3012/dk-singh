<?php

namespace App\Http\Controllers;


use App\Models\Order;
use App\Models\Program;
use App\Models\Transformation;
use App\Models\User;



class DashboardController extends Controller
{
    public function index()
{
    $totalPrograms = Program::count();

    $totalClients = User::count();

    $totalTransformations = Transformation::count();

    $totalOrders = Order::count();

    return view('dashboard.index', compact(

        'totalPrograms',
        'totalClients',
        'totalTransformations',
        'totalOrders'

    ));
}
}