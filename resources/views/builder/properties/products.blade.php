<div class="space-y-6">

    <div class="flex items-center justify-between">

        <h3 class="text-xl font-bold">

            Products

        </h3>

        <button
            wire:click="addProduct"
            class="bg-green-600 hover:bg-green-700 text-white px-3 py-2 rounded-lg text-sm font-semibold">

            ➕ Add

        </button>

    </div>

    {{-- Product List --}}
    <div class="space-y-2">

        @foreach($products as $item)

            <div class="flex items-center gap-2">

                <button
                    wire:click="selectProduct({{ $item->id }})"
                    class="flex-1 text-left p-3 rounded-lg border transition
                    {{ $selectedProductId == $item->id
                        ? 'bg-amber-500 text-white border-amber-500'
                        : 'bg-white hover:bg-gray-50' }}">

                    <div class="font-semibold">

                        {{ $item->name }}

                    </div>

                    <div class="text-xs opacity-70">

                        {{ $item->category }}

                    </div>

                </button>

                <button
                    wire:click="moveProductUp({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬆

                </button>

                <button
                    wire:click="moveProductDown({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border hover:bg-gray-100">

                    ⬇

                </button>

                <button
                    wire:click="duplicateProduct({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border border-blue-500 text-blue-600">

                    📄

                </button>

                <button
                    wire:click="toggleProductFeatured({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->featured ? '⭐' : '☆' }}

                </button>

                <button
                    wire:click="toggleProductStatus({{ $item->id }})"
                    class="px-3 py-3 rounded-lg border">

                    {{ $item->status ? '👁' : '🚫' }}

                </button>

                <button
                    wire:click="deleteProduct({{ $item->id }})"
                    wire:confirm="Delete Product?"
                    class="px-3 py-3 rounded-lg border border-red-500 text-red-600">

                    🗑

                </button>

            </div>

        @endforeach

    </div>

    <hr>

    @if($product)

        <div>

            <label class="block text-sm font-semibold mb-2">
                Product Name
            </label>

            <input
                wire:model.live="product.name"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                SKU
            </label>

            <input
                wire:model.live="product.sku"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Category
            </label>

            <input
                wire:model.live="product.category"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Weight (kg)
            </label>

            <input
                type="number"
                step="0.01"
                wire:model.live="product.weight"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Price
            </label>

            <input
                type="number"
                step="0.01"
                wire:model.live="product.price"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Description
            </label>

            <textarea
                rows="5"
                wire:model.live="product.description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                Product Image
            </label>

            @if(!empty($product['image']))

                <img
                    src="{{ asset('storage/'.$product['image']) }}"
                    class="w-full h-40 object-cover rounded-xl border mb-3">

            @endif

            <input
                type="file"
                wire:model="productImage"
                class="w-full rounded-lg border p-2">

        </div>

        <label class="flex items-center gap-3">

            <input
                type="checkbox"
                wire:model.live="product.featured">

            Featured Product

        </label>

        <div>

            <label class="block text-sm font-semibold mb-2">
                SEO Title
            </label>

            <input
                wire:model.live="product.seo_title"
                class="w-full rounded-lg border p-3">

        </div>

        <div>

            <label class="block text-sm font-semibold mb-2">
                SEO Description
            </label>

            <textarea
                rows="3"
                wire:model.live="product.seo_description"
                class="w-full rounded-lg border p-3"></textarea>

        </div>

        <button
            wire:click="saveProduct"
            class="w-full bg-amber-500 hover:bg-amber-600 text-black py-3 rounded-lg font-bold">

            💾 Save Product

        </button>

    @endif

</div>