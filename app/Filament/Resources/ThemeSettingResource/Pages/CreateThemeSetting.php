<?php

namespace App\Filament\Resources\ThemeSettingResource\Pages;

use App\Filament\Resources\ThemeSettingResource;
use App\Filament\Resources\ThemeSettingResource\Pages\Concerns\HandlesLoginAppearanceUploads;
use App\Models\ThemeSetting;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateThemeSetting extends CreateRecord
{
    use HandlesLoginAppearanceUploads;

    protected static string $resource = ThemeSettingResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return $this->persistThemeSetting(new ThemeSetting, $data);
    }
}
