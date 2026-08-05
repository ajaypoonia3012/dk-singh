<?php

namespace App\Services\Builder;

use Illuminate\Database\Eloquent\Model;

class OrderingService
{
    /**
     * Move record up.
     */
    public function moveUp(Model $model): bool
    {
        $previous = $model::where(
            'sort_order',
            '<',
            $model->sort_order
        )
        ->orderByDesc('sort_order')
        ->first();

        if (!$previous) {
            return false;
        }

        [$model->sort_order, $previous->sort_order] = [
            $previous->sort_order,
            $model->sort_order,
        ];

        $model->save();
        $previous->save();

        return true;
    }

    /**
     * Move record down.
     */
    public function moveDown(Model $model): bool
    {
        $next = $model::where(
            'sort_order',
            '>',
            $model->sort_order
        )
        ->orderBy('sort_order')
        ->first();

        if (!$next) {
            return false;
        }

        [$model->sort_order, $next->sort_order] = [
            $next->sort_order,
            $model->sort_order,
        ];

        $model->save();
        $next->save();

        return true;
    }

    /**
     * Normalize sort_order values.
     */
    public function normalize(string $model): void
    {
        $items = $model::orderBy('sort_order')->get();

        foreach ($items as $index => $item) {
            $item->update([
                'sort_order' => $index + 1,
            ]);
        }
    }
}