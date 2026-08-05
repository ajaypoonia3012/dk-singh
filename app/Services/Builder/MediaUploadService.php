<?php

namespace App\Services\Builder;

use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class MediaUploadService
{
    /**
     * Upload a file.
     */
    public function upload(
        ?TemporaryUploadedFile $file,
        string $directory,
        string $disk = 'public'
    ): ?string {

        if (!$file) {
            return null;
        }

        return $file->store(
            $directory,
            $disk
        );
    }

    /**
     * Upload only if a new file exists.
     */
    public function uploadOrKeep(
        ?TemporaryUploadedFile $file,
        ?string $existing,
        string $directory,
        string $disk = 'public'
    ): ?string {

        if (!$file) {
            return $existing;
        }

        return $this->upload(
            $file,
            $directory,
            $disk
        );
    }

    /**
     * Return storage URL.
     */
    public function url(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        return asset(
            'storage/'.$path
        );
    }
}