<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class WebsiteBuilder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'Website Builder';

    protected static ?string $navigationGroup = 'Website & Growth';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Website Builder';

    protected static string $view = 'filament.pages.website-builder';

    protected ?string $maxContentWidth = 'full';

    protected static bool $shouldRegisterNavigation = true;

    public function getHeading(): string
    {
        return '';
    }

    public function getBreadcrumbs(): array
    {
        return [];
    }
}
