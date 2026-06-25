<?php

namespace App\Filament\Resources\CommunicationLogResource\Pages;

use App\Filament\Resources\CommunicationLogResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunicationLog extends EditRecord
{
    protected static string $resource = CommunicationLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
