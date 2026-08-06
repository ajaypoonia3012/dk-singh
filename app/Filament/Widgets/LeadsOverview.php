<?php

namespace App\Filament\Widgets;

use App\Models\ContactLead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $counts = ContactLead::query()
            ->select('status')
            ->selectRaw('COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');
        $total = $counts->sum();
        $today = ContactLead::query()
            ->whereBetween('created_at', [today(), today()->endOfDay()])
            ->count();

        return [

            Stat::make('Total Leads', $total)
                ->description('All inquiries received')
                ->color('primary'),

            Stat::make(
                'New Leads',
                $counts->get('new', 0)
            )
                ->description('Pending follow-up')
                ->color('warning'),

            Stat::make(
                'Converted Leads',
                $counts->get('converted', 0)
            )
                ->description('Successful conversions')
                ->color('success'),

            Stat::make(
                'Today Leads',
                $today
            )
                ->description('Today inquiries')
                ->color('info'),

        ];
    }
}
