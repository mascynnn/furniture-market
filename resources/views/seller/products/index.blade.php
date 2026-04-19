<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Seller products</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Manage your catalog</h1>
            </div>
            <a href="{{ route('seller.products.create') }}" class="btn-primary">Add new product</a>
        </div>

        <div class="grid gap-6">
            @forelse($products as $product)
                <div class="card-surface p-6 shadow-soft">
                    <div class="grid gap-4 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <h2 class="text-xl font-semibold text-[#333333]">{{ $product->name }}</h2>
                            <p class="mt-2 text-sm text-[#5A4A3A]">{{ Str::limit($product->description, 120) }}</p>
                            <div class="mt-4 flex flex-wrap items-center gap-2 text-sm text-[#5A4A3A]">
                                <span class="badge-wood">{{ $product->is_preorder ? 'Pre-order' : 'Handmade' }}</span>
                                <span>{{ $product->material ?? 'Material unknown' }}</span>
                                <span>Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('seller.products.edit', $product) }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-4 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Edit</a>
                            <form action="{{ route('seller.products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center rounded-xl bg-[#F5E8DA] px-4 py-3 text-sm font-semibold text-[#8B5E3C] hover:bg-[#EAD6B6]">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="card-surface p-8 text-center text-[#5A4A3A]">You have no products yet. Add one to start selling.</div>
            @endforelse
        </div>

        <div class="flex justify-center">
            {{ $products->links() }}
        </div>
    </div>
</x-app-layout>
