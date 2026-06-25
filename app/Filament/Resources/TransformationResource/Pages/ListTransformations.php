<?php

namespace App\Filament\Resources\TransformationResource\Pages;

use App\Filament\Resources\TransformationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransformations extends ListRecords
{
    protected static string $resource = TransformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
