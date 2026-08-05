<?php

namespace App\Data\Media;

class OptimizedMediaData
{
    public function __construct(
        public string $path,
        public string $webpPath,
        public int $width,
        public int $height,
        public int $size
    ) {}
}
