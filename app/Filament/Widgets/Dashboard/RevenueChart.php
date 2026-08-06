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

        $start = Carbon::now()->subMonths(5)->startOfMonth();
        $revenueByMonth = Order::query()
            ->where('payment_status', 'paid')
            ->whereBetween('created_at', [$start, Carbon::now()->endOfMonth()])
            ->get(['amount', 'created_at'])
            ->groupBy(fn (Order $order): string => $order->created_at->format('Y-m'))
            ->map(fn ($orders): float => (float) $orders->sum('amount'));

        for ($i = 5; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $labels[] = $date->format('M');

            $data[] = $revenueByMonth->get($date->format('Y-m'), 0.0);
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
