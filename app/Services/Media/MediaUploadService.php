<?php

namespace App\Services\Media;

use App\Data\Media\UploadedMediaData;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaUploadService
{
    public function upload(TemporaryUploadedFile $file): UploadedMediaData
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = Str::uuid() . '.' . $extension;

        $path = $file->storeAs(
            'media/originals',
            $filename,
            'public'
        );

        return new UploadedMediaData(
            path: $path,
            originalName: $file->getClientOriginalName(),
            mimeType: $file->getMimeType(),
            size: $file->getSize(),
        );
    }
}
