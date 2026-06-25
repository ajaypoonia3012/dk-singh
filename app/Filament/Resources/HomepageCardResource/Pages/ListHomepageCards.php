<?php

namespace App\Filament\Resources\HomepageCardResource\Pages;

use App\Filament\Resources\HomepageCardResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListHomepageCards extends ListRecords
{
    protected static string $resource = HomepageCardResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
