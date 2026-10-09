<?php

namespace App\Services\Builder;

use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class ProductService
{
    public function all()
    {
        return Product::orderBy('sort_order')->get();
    }

    public function active()
    {
        return Product::where('status', true)
            ->orderBy('sort_order')
            ->get();
    }

    public function find(int $id): Product
    {
        return Product::findOrFail($id);
    }

    public function create(): Product
    {
        Gate::authorize('create', Product::class);

        return Product::create([

            'name' => 'New Product',

            'slug' => Str::slug('New Product '.Str::random(6)),

            'sku' => 'SKU-'.strtoupper(Str::random(6)),

            'description' => 'Product Description',

            'image' => null,

            'price' => 0,

            'weight' => 0,

            'category' => 'General',

            'featured' => false,

            'status' => true,

            'sort_order' => Product::max('sort_order') + 1,

            'seo_title' => '',

            'seo_description' => '',

        ]);
    }

    public function update(Product $product, array $data): Product
    {
        Gate::authorize('update', $product);

        if (
            isset($data['name']) &&
            $product->name !== $data['name']
        ) {
            $data['slug'] = Str::slug($data['name']);
        }

        $product->update($data);

        return $product->fresh();
    }

    public function delete(Product $product): void
    {
        Gate::authorize('delete', $product);

        $product->delete();

        Product::orderBy('sort_order')
            ->get()
            ->values()
            ->each(function ($product, $index) {

                $product->update([
                    'sort_order' => $index + 1,
                ]);

            });
    }

    public function duplicate(Product $product): Product
    {
        Gate::authorize('replicate', $product);

        $copy = $product->replicate();

        $copy->name .= ' Copy';

        $copy->slug = Str::slug($copy->name.' '.Str::random(6));

        $copy->sku = 'SKU-'.strtoupper(Str::random(6));

        $copy->sort_order = Product::max('sort_order') + 1;

        $copy->save();

        return $copy;
    }

    public function toggleStatus(Product $product): Product
    {
        Gate::authorize('update', $product);

        $product->update([
            'status' => ! $product->status,
        ]);

        return $product->fresh();
    }

    public function toggleFeatured(Product $product): Product
    {
        Gate::authorize('update', $product);

        $product->update([
            'featured' => ! $product->featured,
        ]);

        return $product->fresh();
    }
}
