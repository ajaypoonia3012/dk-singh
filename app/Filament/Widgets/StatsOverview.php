<?php

namespace App\Filament\Widgets;

use App\Services\DashboardService;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $dashboard = app(DashboardService::class);

        $stats     = $dashboard->getStatsOverview();
        $editorial = $dashboard->getEditorialStats();

        return [

            // ── Commerce & Members ─────────────────────────────────────────
            Stat::make('Revenue', '₹'.number_format($stats['revenue'], 2))
                ->description('Lifetime Revenue')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color('success'),

            Stat::make('Active Members', $stats['active_memberships'])
                ->description('Premium Members')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary'),

            Stat::make('Registered Users', $stats['users'])
                ->description('All-time signups')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),

            Stat::make('Orders', $stats['orders'])
                ->description('Completed Orders')
                ->descriptionIcon('heroicon-m-shopping-cart')
                ->color('warning'),

            // ── Editorial Content ──────────────────────────────────────────
            Stat::make(
                'Published Articles',
                $editorial['articles_published'].' / '.$editorial['articles_total']
            )
                ->description('Drafts: '.$editorial['articles_drafts'])
                ->descriptionIcon('heroicon-m-document-text')
                ->color('success'),

            Stat::make('Exercise Library', $editorial['exercises'])
                ->description('Published exercises')
                ->descriptionIcon('heroicon-m-bolt')
                ->color('info'),

            Stat::make('Media Assets', $editorial['media_count'])
                ->description($editorial['media_missing_alt'].' missing alt text')
                ->descriptionIcon('heroicon-m-photo')
                ->color($editorial['media_missing_alt'] > 0 ? 'warning' : 'success'),

        ];
    }
}
