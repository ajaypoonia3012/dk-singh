<?php

namespace App\Filament\Widgets;

use App\Models\ContactLead;
use Filament\Widgets\ChartWidget;

class LeadsChart extends ChartWidget
{
    protected static ?string $heading = 'Lead Analytics';

    protected function getData(): array
    {
        $days = collect(range(6, 0))->map(function ($day) {

            return now()->subDays($day);

        });

        return [

            'datasets' => [
                [
                    'label' => 'Leads',
                    'data' => $days->map(function ($date) {

                        return ContactLead::whereDate(
                            'created_at',
                            $date
                        )->count();

                    }),

                ],
            ],

            'labels' => $days->map(function ($date) {

                return $date->format('d M');

            }),

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}