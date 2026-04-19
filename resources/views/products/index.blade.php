<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Browse furniture</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Find the right handmade product for your home.</h1>
            </div>
            <a href="{{ route('cart.index') }}" class="btn-primary">View cart</a>
        </div>

        <div class="grid gap-6 xl:grid-cols-[280px_1fr]">
            <aside class="card-surface p-6">
                <h2 class="text-lg font-semibold text-[#333333]">Filter</h2>
                <form method="GET" action="{{ route('products.index') }}" class="mt-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Search</label>
                        <input type="search" name="search" value="{{ request('search') }}" placeholder="Product name or style" class="form-control mt-2" />
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Material</label>
                        <select name="material" class="form-control mt-2">
                            <option value="">All materials</option>
                            <option value="Wood" {{ request('material') === 'Wood' ? 'selected' : '' }}>Wood</option>
                            <option value="Rattan" {{ request('material') === 'Rattan' ? 'selected' : '' }}>Rattan</option>
                            <option value="Upholstery" {{ request('material') === 'Upholstery' ? 'selected' : '' }}>Upholstery</option>
                            <option value="Metal" {{ request('material') === 'Metal' ? 'selected' : '' }}>Metal</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Price range</label>
                        <div class="mt-2 grid gap-3 sm:grid-cols-2">
                            <input type="number" name="min_price" value="{{ request('min_price') }}" placeholder="Min" class="form-control" />
                            <input type="number" name="max_price" value="{{ request('max_price') }}" placeholder="Max" class="form-control" />
                        </div>
                    </div>
                    <button type="submit" class="btn-primary w-full">Apply filters</button>
                </form>
            </aside>

            <div class="space-y-6">
                <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3">
                    @foreach($products as $product)
                        <article class="card-surface overflow-hidden">
                            <div class="h-56 bg-[#F5F1EB]">
                                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                            </div>
                            <div class="p-5">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <h3 class="text-lg font-semibold text-[#333333]">{{ $product->name }}</h3>
                                        <p class="mt-1 text-xs uppercase tracking-[0.25em] text-[#8B5E3C]">{{ $product->user->is_verified ? 'Verified Seller' : 'Seller pending' }}</p>
                                    </div>
                                    <span class="badge-wood">{{ $product->is_preorder ? 'Pre-order' : 'Handmade' }}</span>
                                </div>
                                <p class="mt-4 text-sm text-[#5A4A3A] line-clamp-3">{{ Str::limit($product->description, 100) }}</p>
                                <div class="mt-6 flex items-center justify-between text-sm text-[#5A4A3A]">
                                    <span>{{ $product->material ?? 'Natural material' }}</span>
                                    <span class="font-semibold text-[#8B5E3C]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                                </div>
                                <a href="{{ route('products.show', $product) }}" class="mt-5 inline-flex w-full items-center justify-center rounded-xl border border-[#E9E5DD] px-4 py-3 text-sm font-semibold text-[#5A4A3A] transition hover:border-wood hover:text-wood">View details</a>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="flex justify-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
