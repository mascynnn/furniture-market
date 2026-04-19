<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-6">
        <div class="flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Shopping cart</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Your selected furniture</h1>
            </div>
            <a href="{{ route('checkout.index') }}" class="btn-primary">Proceed to checkout</a>
        </div>

        @if($products->isEmpty())
            <div class="card-surface p-8 text-center text-[#5A4A3A]">Your cart is empty. Add a product to continue.</div>
        @else
            <div class="grid gap-8 lg:grid-cols-[1.4fr_0.6fr]">
                <div class="space-y-4">
                    @foreach($products as $product)
                        <div class="rounded-[24px] bg-white p-6 shadow-soft">
                            <div class="flex flex-col gap-6 lg:flex-row lg:items-center lg:justify-between">
                                <div class="flex items-center gap-5">
                                    <div class="h-28 w-28 overflow-hidden rounded-3xl bg-[#F5F1EB]">
                                        <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1524758631624-e2822e304c36?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                                    </div>
                                    <div>
                                        <h2 class="text-lg font-semibold text-[#333333]">{{ $product->name }}</h2>
                                        <p class="mt-1 text-sm text-[#5A4A3A]">{{ $product->material ?? 'Handmade' }}</p>
                                        <p class="mt-2 text-sm text-[#8B5E3C]">Rp{{ number_format($product->price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="space-y-3 text-sm text-[#5A4A3A]">
                                    <form action="{{ route('cart.update', $product) }}" method="POST" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $cart[$product->id] ?? 1 }}" min="1" class="form-control w-24" />
                                        <button type="submit" class="rounded-xl bg-[#F5E8DA] px-4 py-2 text-sm font-semibold text-[#8B5E3C]">Update</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $product) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-[#D04B1D] hover:text-[#8B5E3C]">Remove</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-[28px] bg-white p-6 shadow-soft">
                    <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Order summary</p>
                    <div class="mt-6 space-y-4">
                        <div class="flex items-center justify-between text-sm text-[#5A4A3A]">
                            <span>Items</span>
                            <span>{{ $products->count() }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm text-[#5A4A3A]">
                            <span>Total</span>
                            <span class="text-lg font-semibold text-[#8B5E3C]">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <a href="{{ route('checkout.index') }}" class="mt-6 btn-primary w-full">Continue to checkout</a>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
