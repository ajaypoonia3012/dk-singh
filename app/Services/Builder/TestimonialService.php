<?php

namespace App\Services\Builder;

use App\Models\Testimonial;

class TestimonialService
{
    public function all()
    {
        return Testimonial::orderBy('sort_order')->get();
    }

    public function active()
    {
        return Testimonial::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id): Testimonial
    {
        return Testimonial::findOrFail($id);
    }

    public function create(): Testimonial
    {
        return Testimonial::create([

            'name' => 'New Testimonial',

            'location' => '',

            'profession' => '',

            'transformation' => '',

            'program' => '',

            'review' => '',

            'image' => null,

            'rating' => 5,

            'featured' => false,

            'status' => true,

            'sort_order' => Testimonial::max('sort_order') + 1,

            'seo_title' => '',

            'seo_description' => '',

        ]);
    }

    public function update(Testimonial $testimonial, array $data): Testimonial
    {
        $testimonial->update($data);

        return $testimonial->fresh();
    }

    public function delete(Testimonial $testimonial): void
    {
        $testimonial->delete();

        Testimonial::orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($item, $index) {

                $item->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(Testimonial $testimonial): Testimonial
    {
        $copy = $testimonial->replicate();

        $copy->name .= ' Copy';

        $copy->sort_order = Testimonial::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggleStatus(Testimonial $testimonial): Testimonial
    {
        $testimonial->update([
            'status' => !$testimonial->status,
        ]);

        return $testimonial->fresh();
    }

    public function toggleFeatured(Testimonial $testimonial): Testimonial
    {
        $testimonial->update([
            'featured' => !$testimonial->featured,
        ]);

        return $testimonial->fresh();
    }
}