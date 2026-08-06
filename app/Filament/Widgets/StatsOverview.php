<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $dashboard = app(DashboardService::class);

        $stats = $dashboard->getStatsOverview();

        return [

            Stat::make(
                'Revenue',
                '₹'.number_format($stats['revenue'], 2)
            )
                ->description('Lifetime Revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make(
                'Orders',
                $stats['orders']
            )
                ->description('Completed Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),

            Stat::make(
                'Active Members',
                $stats['active_memberships']
            )
                ->description('Premium Members')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make(
                'Customers',
                $stats['users']
            )
                ->description('Registered Users')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make(
                'Workout Plans',
                $stats['workouts']
            )
                ->description('Published Workouts')
                ->descriptionIcon('heroicon-m-fire')
                ->color('danger'),

            Stat::make(
                'Diet Plans',
                $stats['diet_plans']
            )
                ->description('Published Diet Plans')
                ->descriptionIcon('heroicon-m-heart')
                ->color('success'),

            Stat::make(
                'Products',
                $stats['products']
            )
                ->description('Supplements')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),

        ];
    }
}
