<?php

namespace App\Filament\Resources\AdminResource\Widgets;

use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';

    protected function getData(): array
    {
        return [

            'datasets' => [
                [
                    'label' => 'Revenue',

                    'data' => [
                        12000,
                        19000,
                        30000,
                        25000,
                        40000,
                        55000,
                    ],
                ],
            ],

            'labels' => [
                'Jan',
                'Feb',
                'Mar',
                'Apr',
                'May',
                'Jun',
            ],
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}