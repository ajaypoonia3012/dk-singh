<?php

namespace App\Services;

use App\Models\DietPlan;
use App\Models\Membership;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\WorkoutPlan;

class DashboardService
{
    public function getStatsOverview(): array
    {
        $orders = Order::query()
            ->selectRaw('COUNT(*) as aggregate')
            ->selectRaw(
                'COALESCE(SUM(CASE WHEN payment_status = ? THEN amount ELSE 0 END), 0) as revenue',
                ['paid']
            )
            ->first();

        return [
            'revenue' => (float) $orders->revenue,
            'orders' => (int) $orders->aggregate,
            'active_memberships' => Membership::query()
                ->where('status', true)
                ->where('expires_at', '>=', now())
                ->count(),
            'users' => User::query()->count(),
            'workouts' => WorkoutPlan::query()->count(),
            'diet_plans' => DietPlan::query()->count(),
            'products' => Product::query()->count(),
        ];
    }

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

            'today' => Order::whereBetween('created_at', [today(), today()->endOfDay()])
                ->where('payment_status', 'paid')
                ->sum('amount'),

            'this_month' => Order::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])
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
