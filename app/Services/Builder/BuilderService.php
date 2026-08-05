<?php

namespace App\Services\Builder;

class BuilderService
{
    /**
     * Refresh a collection ordered by sort_order.
     */
    public function refresh(string $model)
    {
        return $model::orderBy('sort_order')->get();
    }

    /**
     * Find a record by id.
     */
    public function find(string $model, int $id)
    {
        return $model::findOrFail($id);
    }

    /**
     * Save any Eloquent model.
     */
    public function save($model): void
    {
        $model->save();
    }

    /**
     * Delete any Eloquent model.
     */
    public function delete($model): void
    {
        $model->delete();
    }

    /**
     * Toggle active status.
     */
    public function toggle($model): void
    {
        if (!isset($model->is_active)) {
            return;
        }

        $model->is_active = ! $model->is_active;

        $model->save();
    }

    /**
     * Get next sort order.
     */
    public function nextSortOrder(string $model): int
    {
        return ((int) $model::max('sort_order')) + 1;
    }
}