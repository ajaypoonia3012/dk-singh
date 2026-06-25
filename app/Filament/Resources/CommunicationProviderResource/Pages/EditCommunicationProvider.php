<?php

namespace App\Filament\Resources\CommunicationProviderResource\Pages;

use App\Filament\Resources\CommunicationProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunicationProvider extends EditRecord
{
    protected static string $resource = CommunicationProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
