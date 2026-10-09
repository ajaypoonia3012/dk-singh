<?php

namespace App\Services\Media;

use App\Data\Media\UploadedMediaData;
use App\Models\Media;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaUploadService
{
    public function upload(TemporaryUploadedFile $file): UploadedMediaData
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $filename = Str::uuid().'.'.$extension;

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

    public function uploadImageToLibrary(TemporaryUploadedFile $file): Media
    {
        $uploaded = $this->upload($file);

        try {
            $dimensions = @getimagesize(Storage::disk('public')->path($uploaded->path));

            return Media::query()->create([
                'name' => pathinfo($uploaded->originalName, PATHINFO_FILENAME),
                'file_name' => basename($uploaded->path),
                'disk' => 'public',
                'folder' => 'media/originals',
                'media_category_id' => null,
                'path' => $uploaded->path,
                'mime_type' => $uploaded->mimeType,
                'size' => $uploaded->size,
                'width' => is_array($dimensions) ? $dimensions[0] : null,
                'height' => is_array($dimensions) ? $dimensions[1] : null,
                'type' => 'image',
                'active' => true,
                'featured' => false,
            ]);
        } catch (\Throwable $exception) {
            Storage::disk('public')->delete($uploaded->path);

            throw $exception;
        }
    }
}
