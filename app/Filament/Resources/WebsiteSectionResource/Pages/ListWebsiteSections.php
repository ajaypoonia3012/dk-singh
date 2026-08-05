<?php

namespace App\Filament\Resources\WebsiteSectionResource\Pages;

use App\Filament\Resources\WebsiteSectionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWebsiteSections extends ListRecords
{
    protected static string $resource = WebsiteSectionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
