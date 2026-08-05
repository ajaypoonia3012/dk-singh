<?php

namespace App\Filament\Widgets\Dashboard;

use App\Models\Order;
use App\Models\User;
use App\Models\Membership;
use Filament\Widgets\Widget;

class ActivityFeed extends Widget
{
    protected static string $view = 'filament.widgets.dashboard.activity-feed';

    protected int|string|array $columnSpan = 'full';

    protected function getViewData(): array
    {
        return [

            'orders' => Order::latest()->take(5)->get(),

            'users' => User::latest()->take(5)->get(),

            'memberships' => Membership::latest()->take(5)->get(),

        ];
    }
}