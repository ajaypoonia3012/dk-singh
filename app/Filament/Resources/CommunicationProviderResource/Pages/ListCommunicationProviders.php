<?php

namespace App\Filament\Resources\CommunicationProviderResource\Pages;

use App\Filament\Resources\CommunicationProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCommunicationProviders extends ListRecords
{
    protected static string $resource = CommunicationProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
