<?php

namespace App\Services\Builder;

use App\Models\Transformation;
use Illuminate\Support\Str;

class TransformationService
{
    public function all()
    {
        return Transformation::orderBy('sort_order')->get();
    }

    public function active()
    {
        return Transformation::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id): Transformation
    {
        return Transformation::findOrFail($id);
    }

    public function create(): Transformation
    {
        return Transformation::create([

            'name' => 'New Transformation',

            'slug' => Str::slug('New Transformation'),

            'before_image' => null,

            'after_image' => null,

            'image' => null,

            'goal' => 'Fat Loss',

            'program' => 'Transformation Program',

            'coach' => 'DK Singh',

            'before_weight' => 0,

            'after_weight' => 0,

            'weight_loss' => 0,

            'duration' => '12 Weeks',

            'story' => '',

            'review' => '',

            'result' => '',

            'description' => 'Transformation description',

            'featured' => false,

            'status' => true,

            'sort_order' => Transformation::max('sort_order') + 1,

            'seo_title' => '',

            'seo_description' => '',

        ]);
    }

    public function update(
        Transformation $transformation,
        array $data
    ): Transformation
    {
        if (
            isset($data['name']) &&
            $transformation->name !== $data['name']
        ) {

            $data['slug'] = Str::slug($data['name']);

        }

        $transformation->update($data);

        return $transformation->fresh();
    }

    public function delete(
        Transformation $transformation
    ): void
    {
        $transformation->delete();

        Transformation::orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($item, $index) {

                $item->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(
        Transformation $transformation
    ): Transformation
    {
        $copy = $transformation->replicate();

        $copy->name .= ' Copy';

        $copy->slug = Str::slug($copy->name);

        $copy->sort_order =
            Transformation::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggleStatus(
        Transformation $transformation
    ): Transformation
    {
        $transformation->update([
            'status' => !$transformation->status,
        ]);

        return $transformation->fresh();
    }

    public function toggleFeatured(
        Transformation $transformation
    ): Transformation
    {
        $transformation->update([
            'featured' => !$transformation->featured,
        ]);

        return $transformation->fresh();
    }
}