<?php

namespace App\Filament\Resources\ActionPlanResource\Pages;

use App\Filament\Resources\ActionPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActionPlans extends ListRecords
{
    protected static string $resource = ActionPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
