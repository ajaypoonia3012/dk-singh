<?php

namespace App\Services\Media;

use App\Models\Media;

class MediaLinker
{
    /**
     * Link a single image column to media_id.
     */
    public function link($model, string $imageColumn = 'image', string $mediaColumn = 'media_id'): bool
    {
        if (empty($model->{$imageColumn})) {
            return false;
        }

        $path = trim($model->{$imageColumn}, '/');

        $media = Media::where('path', $path)->first();

        if (! $media) {
            return false;
        }

        $model->{$mediaColumn} = $media->id;
        $model->save();

        return true;
    }

    /**
     * Link a collection of models using one image column.
     */
    public function linkCollection(
        $query,
        string $imageColumn = 'image',
        string $mediaColumn = 'media_id'
    ): int {
        $count = 0;

        $query->each(function ($model) use (&$count, $imageColumn, $mediaColumn) {

            if ($this->link($model, $imageColumn, $mediaColumn)) {
                $count++;
            }

        });

        return $count;
    }

    /**
     * Link a Transformation (before + after images).
     */
    public function linkTransformation($transformation): int
    {
        $count = 0;

        if (
            $this->link(
                $transformation,
                'before_image',
                'before_media_id'
            )
        ) {
            $count++;
        }

        if (
            $this->link(
                $transformation,
                'after_image',
                'after_media_id'
            )
        ) {
            $count++;
        }

        return $count;
    }

    /**
     * Link all Transformation records.
     */
    public function linkTransformationCollection($query): int
    {
        $count = 0;

        $query->each(function ($transformation) use (&$count) {

            $count += $this->linkTransformation($transformation);

        });

        return $count;
    }
}