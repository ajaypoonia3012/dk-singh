<?php

namespace App\Filament\Resources\CoachNoteResource\Pages;

use App\Filament\Resources\CoachNoteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCoachNotes extends ListRecords
{
    protected static string $resource = CoachNoteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
