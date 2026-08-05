<?php

namespace App\Filament\Resources\MediaResource\Pages;

use App\Filament\Resources\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $absolute = storage_path('app/public/' . $data['path']);

        if (file_exists($absolute)) {

            $image = (new ImageManager(new Driver()))->read($absolute);

            $data['file_name'] = basename($data['path']);

            if (empty($data['name'])) {
                $data['name'] = pathinfo($data['path'], PATHINFO_FILENAME);
            }

            $data['disk'] = 'public';

            $data['mime_type'] = mime_content_type($absolute);

            $data['size'] = filesize($absolute);

            $data['width'] = $image->width();

            $data['height'] = $image->height();

            $data['type'] = 'image';
        }

        return $data;
    }
}
