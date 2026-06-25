<?php

namespace App\Filament\Widgets;

use App\Models\ContactLead;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeadsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [

            Stat::make('Total Leads', ContactLead::count())
                ->description('All inquiries received')
                ->color('primary'),

            Stat::make(
                'New Leads',
                ContactLead::where('status', 'new')->count()
            )
                ->description('Pending follow-up')
                ->color('warning'),

            Stat::make(
                'Converted Leads',
                ContactLead::where('status', 'converted')->count()
            )
                ->description('Successful conversions')
                ->color('success'),

            Stat::make(
                'Today Leads',
                ContactLead::whereDate('created_at', today())->count()
            )
                ->description('Today inquiries')
                ->color('info'),

        ];
    }
}