<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Membership;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class MembershipChart extends ChartWidget
{
    protected static ?string $heading = 'Membership Growth';

    protected function getData(): array
    {
        $labels = [];
        $data = [];

        for ($i = 5; $i >= 0; $i--) {

            $date = Carbon::now()->subMonths($i);

            $labels[] = $date->format('M');

            $data[] = Membership::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
        }

        return [

            'datasets' => [
                [
                    'label' => 'New Memberships',
                    'data' => $data,
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16,185,129,.15)',
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

    protected function getMaxHeight(): ?string
    {
        return '320px';
    }
}