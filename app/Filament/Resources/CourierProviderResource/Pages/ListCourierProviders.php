<?php

namespace App\Filament\Resources\CourierProviderResource\Pages;

use App\Filament\Resources\CourierProviderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCourierProviders extends ListRecords
{
    protected static string $resource = CourierProviderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
