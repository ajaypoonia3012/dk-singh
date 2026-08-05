<?php

namespace App\Services\Builder;

use Illuminate\Database\Eloquent\Model;

class CrudService
{
    /**
     * Create a new model.
     */
    public function create(string $model, array $data): Model
    {
        return $model::create($data);
    }

    /**
     * Update an existing model.
     */
    public function update(Model $model, array $data): Model
    {
        $model->update($data);

        return $model->fresh();
    }

    /**
     * Delete a model.
     */
    public function delete(Model $model): bool
    {
        return (bool) $model->delete();
    }

    /**
     * Duplicate a model.
     */
    public function duplicate(Model $model): Model
    {
        $copy = $model->replicate();

        if (isset($copy->sort_order)) {
            $copy->sort_order++;
        }

        $copy->save();

        return $copy;
    }

    /**
     * Toggle active/inactive.
     */
    public function toggle(Model $model): Model
    {
        if (property_exists($model, 'is_active') || isset($model->is_active)) {

            $model->is_active = ! $model->is_active;

            $model->save();
        }

        return $model;
    }
}