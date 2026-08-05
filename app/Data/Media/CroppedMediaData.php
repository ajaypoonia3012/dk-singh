<?php

namespace App\Data\Media;

class CroppedMediaData
{
    public function __construct(
        public string $path,
        public int $width,
        public int $height
    ) {}
}
