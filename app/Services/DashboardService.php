<?php

namespace App\Services;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Membership;
use App\Models\WorkoutPlan;
use App\Models\DietPlan;

class DashboardService
{
    /*
    |--------------------------------------------------------------------------
    | OVERVIEW
    |--------------------------------------------------------------------------
    */

    public function getOverview(): array
    {
        return [

            'users' => User::count(),

            'memberships' => Membership::count(),

            'active_memberships' => Membership::where('status', true)
                ->where('expires_at', '>=', now())
                ->count(),

            'orders' => Order::count(),

            'products' => Product::count(),

            'workouts' => WorkoutPlan::count(),

            'diet_plans' => DietPlan::count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | REVENUE
    |--------------------------------------------------------------------------
    */

    public function getRevenue(): array
    {
        return [

            'total' => Order::where('payment_status', 'paid')
                ->sum('amount'),

            'today' => Order::whereDate('created_at', today())
                ->where('payment_status', 'paid')
                ->sum('amount'),

            'this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->where('payment_status', 'paid')
                ->sum('amount'),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MEMBERSHIPS
    |--------------------------------------------------------------------------
    */

    public function getMembershipStats(): array
    {
        return [

            'active' => Membership::where('status', true)
                ->where('expires_at', '>=', now())
                ->count(),

            'expired' => Membership::where('expires_at', '<', now())
                ->count(),

            'expiring_soon' => Membership::whereBetween(
                'expires_at',
                [now(), now()->addDays(7)]
            )->count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | PRODUCTS
    |--------------------------------------------------------------------------
    */

    public function getProductStats(): array
    {
        return [

            'products' => Product::count(),

            'featured' => Product::where('featured', true)->count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | WORKOUTS
    |--------------------------------------------------------------------------
    */

    public function getWorkoutStats(): array
    {
        return [

            'workouts' => WorkoutPlan::count(),

            'diet_plans' => DietPlan::count(),

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | LATEST
    |--------------------------------------------------------------------------
    */

    public function getLatestOrders(int $limit = 10)
    {
        return Order::latest()->take($limit)->get();
    }

    public function getLatestMembers(int $limit = 10)
    {
        return Membership::latest()->take($limit)->get();
    }

    public function getLatestUsers(int $limit = 10)
    {
        return User::latest()->take($limit)->get();
    }
}