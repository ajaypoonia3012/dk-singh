<?php

namespace App\Services\Builder;

use App\Models\Program;
use Illuminate\Support\Str;

class ProgramService
{
    public function all()
    {
        return Program::orderBy('sort_order')->get();
    }

    public function active()
    {
        return Program::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id): Program
    {
        return Program::findOrFail($id);
    }

    public function create(): Program
    {
        return Program::create([

            'title' => 'New Program',

            'slug' => Str::slug('New Program'),

            'description' => 'Program Description',

            'image' => null,

            'category' => 'Fitness',

            'duration' => '30 Days',

            'price' => 0,

            'featured' => false,

            'status' => true,

            'sort_order' => Program::max('sort_order') + 1,

            'seo_title' => '',

            'seo_description' => '',

        ]);
    }

    public function update(Program $program, array $data): Program
    {
        if (
            isset($data['title']) &&
            $program->title !== $data['title']
        ) {
            $data['slug'] = Str::slug($data['title']);
        }

        $program->update($data);

        return $program->fresh();
    }

    public function delete(Program $program): void
    {
        $program->delete();

        Program::orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($program, $index) {

                $program->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(Program $program): Program
    {
        $copy = $program->replicate();

        $copy->title .= ' Copy';

        $copy->slug = Str::slug($copy->title);

        $copy->sort_order = Program::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggleStatus(Program $program): Program
    {
        $program->update([
            'status' => !$program->status
        ]);

        return $program->fresh();
    }

    public function toggleFeatured(Program $program): Program
    {
        $program->update([
            'featured' => !$program->featured
        ]);

        return $program->fresh();
    }
}