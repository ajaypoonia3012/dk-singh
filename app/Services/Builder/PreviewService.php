<?php

namespace App\Services\Builder;

class PreviewService
{
    /**
     * Refresh preview data.
     */
    public function refresh(string $model)
    {
        return $model::orderBy('sort_order')->get();
    }

    /**
     * Reload a single record.
     */
    public function reload(string $model, int $id)
    {
        return $model::findOrFail($id);
    }

    /**
     * Refresh an entire builder state.
     */
    public function refreshState(array &$state, string $key, $value): void
    {
        $state[$key] = $value;
    }

    /**
     * Refresh selected record.
     */
    public function refreshSelection(&$selected, $record): void
    {
        $selected = $record;
    }

    /**
     * Reset temporary uploads.
     */
    public function clearUpload(&$upload): void
    {
        $upload = null;
    }
}