<?php

namespace App\Filament\Resources\TransformationPhotoResource\Pages;

use App\Filament\Resources\TransformationPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransformationPhotos extends ListRecords
{
    protected static string $resource = TransformationPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
