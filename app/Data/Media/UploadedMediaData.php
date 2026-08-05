<?php

namespace App\Data\Media;

class UploadedMediaData
{
    public function __construct(
        public string $path,
        public string $originalName,
        public string $mimeType,
        public int $size
    ) {}
}
