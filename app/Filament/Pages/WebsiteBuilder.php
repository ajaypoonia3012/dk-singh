<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class WebsiteBuilder extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'Website Builder';

    protected static ?string $navigationGroup = 'Website Builder';

    protected static ?string $title = 'Website Builder';

    protected static string $view = 'filament.pages.website-builder';

    protected ?string $maxContentWidth = 'full';

    protected static bool $shouldRegisterNavigation = true;
}