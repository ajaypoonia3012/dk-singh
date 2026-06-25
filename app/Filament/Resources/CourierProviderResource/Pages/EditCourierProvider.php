<?php

namespace App\Filament\Resources\CourierProviderResource\Pages;

use App\Filament\Resources\CourierProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCourierProvider extends EditRecord
{
    protected static string $resource = CourierProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
