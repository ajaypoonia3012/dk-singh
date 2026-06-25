<?php

namespace App\Filament\Resources\ActionPlanResource\Pages;

use App\Filament\Resources\ActionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActionPlan extends EditRecord
{
    protected static string $resource = ActionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
