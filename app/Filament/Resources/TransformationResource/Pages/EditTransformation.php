<?php

namespace App\Filament\Resources\TransformationResource\Pages;

use App\Filament\Resources\TransformationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTransformation extends EditRecord
{
    protected static string $resource = TransformationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
