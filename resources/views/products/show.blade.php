<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6 rounded-[28px] bg-white p-8 shadow-soft">
                <div class="overflow-hidden rounded-[24px] bg-[#F5F1EB]">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=1200&q=60' }}" alt="{{ $product->name }}" class="h-[520px] w-full object-cover" />
                </div>
                <div class="grid gap-3 md:grid-cols-3">
                    <div class="rounded-3xl bg-[#FDFDFC] p-5 text-sm text-[#5A4A3A]">Material: {{ $product->material ?? 'Natural wood' }}</div>
                    <div class="rounded-3xl bg-[#FDFDFC] p-5 text-sm text-[#5A4A3A]">Size: {{ $product->size ?? 'Custom' }}</div>
                    <div class="rounded-3xl bg-[#FDFDFC] p-5 text-sm text-[#5A4A3A]">Seller: {{ $product->user->name }}</div>
                </div>
            </div>

            <aside class="space-y-6 rounded-[28px] bg-white p-8 shadow-soft">
                <div class="space-y-4">
                    <div class="flex flex-wrap items-center gap-3">
                        <h1 class="text-3xl font-semibold text-[#333333]">{{ $product->name }}</h1>
                        @if($product->is_preorder)
                            <span class="badge-wood">Pre-order (7 days production)</span>
                        @else
                            <span class="badge-wood">Handmade</span>
                        @endif
                    </div>
                    <div class="flex items-center gap-3 text-sm text-[#5A4A3A]">
                        <span class="inline-flex items-center rounded-full bg-[#F5E8DA] px-3 py-1 text-[#8B5E3C]">Verified Seller</span>
                        <span>{{ $product->user->name }}</span>
                    </div>
                    <p class="text-2xl font-semibold text-[#8B5E3C]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                </div>

                <div class="space-y-4 text-sm leading-6 text-[#5A4A3A]">
                    <p>{{ $product->description ?? 'A quality handmade furniture piece built for comfort and longevity.' }}</p>
                </div>

                <form action="{{ route('cart.add') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}" />
                    <div>
                        <label class="block text-sm font-medium text-[#5A4A3A]">Quantity</label>
                        <input type="number" name="quantity" value="1" min="1" class="form-control mt-2 w-full" />
                    </div>
                    <button type="submit" class="btn-primary w-full">Add to cart</button>
                </form>

                <a href="{{ route('chat.show', $product->user) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-[#E9E5DD] px-4 py-3 text-sm font-semibold text-[#5A4A3A] transition hover:border-wood hover:text-wood">Chat seller</a>
            </aside>
        </div>
    </div>
</x-app-layout>
