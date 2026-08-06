<?php

namespace App\Filament\Resources\ThemeSettingResource\Pages;

use App\Filament\Resources\ThemeSettingResource;
use App\Models\ThemeSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditThemeSetting extends EditRecord
{
    protected static string $resource = ThemeSettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var ThemeSetting $record */
        $record = $this->getRecord();

        $data = array_replace($data, $record->groupedConfigurationForForm());
        $data['theme_name'] ??= 'Default';
        $data['primary_color'] ??= '#facc15';
        $data['secondary_color'] ??= '#111111';
        $data['accent_color'] ??= '#ffffff';

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var ThemeSetting $record */
        $relationalData = $record->fillGroupedConfigurationFromForm($data);

        $record->fill($relationalData);
        $record->save();

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
