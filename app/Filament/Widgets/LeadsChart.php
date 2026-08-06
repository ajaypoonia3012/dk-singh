<?php

namespace App\Filament\Widgets;

use App\Models\ContactLead;
use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = 'Lead Analytics';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(fn (int $day) => now()->subDays($day)->startOfDay());
        $counts = ContactLead::query()
            ->whereBetween('created_at', [$days->first(), $days->last()->copy()->endOfDay()])
            ->get(['created_at'])
            ->countBy(fn (ContactLead $lead): string => $lead->created_at->toDateString());

        return [

            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $days->map(fn ($date): int => $counts->get($date->toDateString(), 0)),

                ],
            ],

            'labels' => $days->map(fn ($date): string => $date->format('d M')),

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
