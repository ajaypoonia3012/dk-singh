<?php

namespace App\Filament\Resources\ThemeSettingResource\Pages;

use App\Filament\Resources\ThemeSettingResource;
use App\Filament\Resources\ThemeSettingResource\Pages\Concerns\HandlesLoginAppearanceUploads;
use App\Models\ThemeSetting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditThemeSetting extends EditRecord
{
    use HandlesLoginAppearanceUploads;

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
        return $this->persistThemeSetting($record, $data);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('openWebsiteBuilder')
                ->label('🎨 Visual Theme & Builder')
                ->url('/admin/website-builder')
                ->color('warning')
                ->icon('heroicon-o-paint-brush'),
            Actions\DeleteAction::make(),
        ];
    }
}
