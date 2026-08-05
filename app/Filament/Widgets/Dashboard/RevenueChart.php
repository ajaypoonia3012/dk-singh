<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Order;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class RevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Revenue';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        // Last 6 months
        for ($i = 5; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $labels[] = $date->format('M');

            $revenue = Order::where('payment_status', 'paid')
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->sum('amount');

            $data[] = (float) $revenue;
        }

        return [

            'datasets' => [

                [

                    'label' => 'Revenue (₹)',

                    'data' => $data,

                    'borderColor' => '#f59e0b',

                    'backgroundColor' => 'rgba(245,158,11,0.15)',

                    'fill' => true,

                    'tension' => 0.4,

                ],

            ],

            'labels' => $labels,

        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}