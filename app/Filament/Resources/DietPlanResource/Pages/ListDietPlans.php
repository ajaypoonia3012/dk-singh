<?php

namespace App\Filament\Resources\DietPlanResource\Pages;

use App\Filament\Resources\DietPlanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListDietPlans extends ListRecords
{
    protected static string $resource = DietPlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
