<x-app-layout>
    <div class="mx-auto max-w-3xl space-y-8">
        <div>
            <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Edit product</p>
            <h1 class="text-3xl font-semibold text-[#333333]">Update your product details</h1>
        </div>

        <div class="rounded-[28px] bg-white p-8 shadow-soft">
            <form action="{{ route('seller.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-[#5A4A3A]">Product name</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" class="form-control mt-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#5A4A3A]">Price</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" class="form-control mt-2" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#5A4A3A]">Description</label>
                    <textarea name="description" rows="4" class="form-control mt-2">{{ old('description', $product->description) }}</textarea>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Material</label>
                        <input type="text" name="material" value="{{ old('material', $product->material) }}" class="form-control mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Size</label>
                        <input type="text" name="size" value="{{ old('size', $product->size) }}" class="form-control mt-2" />
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-[#5A4A3A]">Product image</label>
                    <input type="file" name="image" class="mt-2 w-full text-sm text-[#5A4A3A]" />
                </div>
                <div class="flex items-center gap-3">
                    <input id="preorder" name="is_preorder" type="checkbox" {{ $product->is_preorder ? 'checked' : '' }} class="h-5 w-5 rounded border-[#E9E5DD] text-wood focus:ring-wood" />
                    <label for="preorder" class="text-sm text-[#5A4A3A]">Enable pre-order mode</label>
                </div>
                <div class="flex items-center gap-3">
                    <button type="submit" class="btn-primary">Update product</button>
                    <a href="{{ route('seller.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
