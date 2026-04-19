<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="rounded-[28px] bg-white p-8 shadow-soft">
            <div class="grid gap-8 lg:grid-cols-[1.3fr_0.7fr]">
                <div class="space-y-6">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Checkout</p>
                        <h1 class="text-3xl font-semibold text-[#333333]">Complete your order</h1>
                    </div>
                    <form action="{{ route('checkout.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-[#5A4A3A]">Delivery address</label>
                            <textarea name="address" rows="4" class="form-control mt-2">{{ old('address') }}</textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-[#5A4A3A]">Upload payment proof</label>
                            <input type="file" name="payment_proof" class="mt-2 w-full text-sm text-[#5A4A3A]" />
                            <p class="mt-2 text-sm text-[#8B5E3C]">Transfer to bank account then upload the transfer slip.</p>
                        </div>
                        <button type="submit" class="btn-primary">Place order</button>
                    </form>
                </div>

                <aside class="space-y-6 rounded-[28px] bg-[#FDFDFC] p-6 shadow-card">
                    <div class="space-y-3">
                        <h2 class="text-xl font-semibold text-[#333333]">Order summary</h2>
                        @foreach($products as $product)
                            <div class="flex items-start justify-between gap-3 border-b border-[#E9E5DD] pb-4">
                                <div>
                                    <p class="font-semibold text-[#333333]">{{ $product->name }}</p>
                                    <p class="text-sm text-[#5A4A3A]">Qty {{ $cart[$product->id] ?? 1 }}</p>
                                </div>
                                <p class="font-semibold text-[#8B5E3C]">Rp{{ number_format($product->price * ($cart[$product->id] ?? 1), 0, ',', '.') }}</p>
                            </div>
                        @endforeach
                        <div class="flex items-center justify-between pt-4 text-sm text-[#5A4A3A]">
                            <span>Total</span>
                            <span class="font-semibold text-[#8B5E3C]">Rp{{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="rounded-3xl bg-white p-5 border border-[#E9E5DD]">
                        <p class="text-sm font-semibold text-[#333333]">Bank transfer details</p>
                        <p class="mt-3 text-sm text-[#5A4A3A]">Bank: BCA</p>
                        <p class="text-sm text-[#5A4A3A]">Account: 1234567890</p>
                        <p class="text-sm text-[#5A4A3A]">Name: Furnivo Marketplace</p>
                    </div>
                </aside>
            </div>
        </div>
    </div>
</x-app-layout>
