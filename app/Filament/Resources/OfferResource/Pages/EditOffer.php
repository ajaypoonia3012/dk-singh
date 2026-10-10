<?php

namespace App\Filament\Resources\OfferResource\Pages;

use App\Filament\Resources\OfferResource;
use App\Models\Offer;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->before(function (Actions\DeleteAction $action, Offer $record) {
                    if ($record->orders()->exists()) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot Delete Offer')
                            ->body('This offer is linked to existing customer orders. Please disable or archive it instead to preserve audit records.')
                            ->send();

                        $action->halt();
                    }
                }),
        ];
    }
}
