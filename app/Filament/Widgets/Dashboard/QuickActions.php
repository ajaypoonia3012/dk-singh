<?php

namespace App\Filament\Widgets\Dashboard;

use Filament\Widgets\Widget;

class QuickActions extends Widget
{
    protected static ?int $sort = 2;

    protected static string $view = 'filament.widgets.dashboard.quick-actions';

    protected int|string|array $columnSpan = 'full';
}
