<?php

namespace App\Filament\Resources\SettingResource\Pages;

use App\Filament\Resources\SettingResource;
use App\Models\Setting;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditSetting extends EditRecord
{
    protected static string $resource = SettingResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        /** @var Setting $record */
        $record = $this->getRecord();

        $data = array_replace($data, $record->groupedConfigurationForForm());
        $data['site_name'] ??= config('app.name', 'Laravel');
        $data['meta_keywords'] = $this->keywordsForForm($data['meta_keywords'] ?? null);

        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        /** @var Setting $record */
        $data['meta_keywords'] = $this->keywordsForStorage($data['meta_keywords'] ?? null);
        $relationalData = $record->fillGroupedConfigurationFromForm($data);

        $record->fill($relationalData);
        $record->save();

        return $record;
    }

    /**
     * @return array<int, string>
     */
    private function keywordsForForm(mixed $keywords): array
    {
        if (is_array($keywords)) {
            return array_values(array_filter(array_map('trim', $keywords)));
        }

        if (! is_string($keywords) || trim($keywords) === '') {
            return [];
        }

        return array_values(array_filter(array_map('trim', explode(',', $keywords))));
    }

    private function keywordsForStorage(mixed $keywords): ?string
    {
        $keywords = $this->keywordsForForm($keywords);

        return $keywords === [] ? null : implode(', ', $keywords);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('openWebsiteBuilder')
                ->label('🎨 Visual Website Builder')
                ->url('/admin/website-builder')
                ->color('warning')
                ->icon('heroicon-o-paint-brush'),
            Actions\DeleteAction::make(),
        ];
    }
}
