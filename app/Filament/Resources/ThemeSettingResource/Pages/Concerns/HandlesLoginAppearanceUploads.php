<?php

namespace App\Filament\Resources\ThemeSettingResource\Pages\Concerns;

use App\Models\ThemeSetting;
use App\Services\Media\MediaUploadService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

trait HandlesLoginAppearanceUploads
{
    /**
     * @param  array<string, mixed>  $data
     */
    protected function persistThemeSetting(ThemeSetting $record, array $data): ThemeSetting
    {
        $uploadedPaths = [];

        try {
            return DB::transaction(function () use ($record, $data, &$uploadedPaths): ThemeSetting {
                foreach (['public_login', 'admin_login'] as $prefix) {
                    $upload = $this->temporaryUpload($data["{$prefix}_background_upload"] ?? null);
                    unset($data["{$prefix}_background_upload"]);

                    if (! $upload) {
                        continue;
                    }

                    $media = app(MediaUploadService::class)->uploadImageToLibrary($upload);
                    $uploadedPaths[] = $media->path;
                    $data["{$prefix}_background_media_id"] = $media->id;
                }

                $relationalData = $record->fillGroupedConfigurationFromForm($data);
                $record->fill($relationalData);
                $record->save();

                return $record;
            });
        } catch (\Throwable $exception) {
            foreach ($uploadedPaths as $path) {
                Storage::disk('public')->delete($path);
            }

            throw $exception;
        }
    }

    private function temporaryUpload(mixed $state): ?TemporaryUploadedFile
    {
        if ($state instanceof TemporaryUploadedFile) {
            return $state;
        }

        if (is_array($state)) {
            foreach ($state as $upload) {
                if ($upload instanceof TemporaryUploadedFile) {
                    return $upload;
                }
            }
        }

        return null;
    }
}
