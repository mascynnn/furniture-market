<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Order details</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Order #{{ $order->id }}</h1>
            </div>
            <a href="{{ route('buyer.orders') }}" class="rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Back to orders</a>
        </div>

        <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-6 rounded-[28px] bg-white p-8 shadow-soft">
                <div class="flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Address</p>
                        <p class="mt-3 text-sm text-[#5A4A3A]">{{ $order->address }}</p>
                    </div>
                    <div class="space-y-2 text-sm text-[#5A4A3A]">
                        <p>Status: <span class="font-semibold text-[#333333]">{{ ucfirst($order->status) }}</span></p>
                        <p>Payment: <span class="font-semibold text-[#333333]">{{ ucfirst($order->payment_status) }}</span></p>
                        <p>Shipping: <span class="font-semibold text-[#333333]">{{ ucfirst($order->shipping_status) }}</span></p>
                    </div>
                </div>

                <div class="space-y-4">
                    <h2 class="text-xl font-semibold text-[#333333]">Items</h2>
                    @foreach($order->items as $item)
                        <div class="rounded-[20px] border border-[#E9E5DD] p-5">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold text-[#333333]">{{ $item->product->name }}</p>
                                    <p class="mt-1 text-sm text-[#5A4A3A]">Qty {{ $item->quantity }}</p>
                                </div>
                                <p class="font-semibold text-[#8B5E3C]">Rp{{ number_format($item->price * $item->quantity, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="rounded-[20px] bg-[#FDFDFC] p-5 border border-[#E9E5DD]">
                    <p class="font-semibold text-[#333333]">Payment proof</p>
                    @if($order->payment_proof)
                        <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank" class="mt-3 inline-flex text-sm font-semibold text-[#8B5E3C] hover:text-[#6f4c32]">View uploaded proof</a>
                    @else
                        <p class="mt-3 text-sm text-[#5A4A3A]">No proof uploaded yet.</p>
                    @endif
                </div>
            </div>

            <aside class="rounded-[28px] bg-white p-8 shadow-soft">
                <div class="space-y-4">
                    <div class="flex items-center justify-between text-sm text-[#5A4A3A]">
                        <span>Total</span>
                        <span class="font-semibold text-[#8B5E3C]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</span>
                    </div>
                    @if($order->tracking_number)
                        <div class="space-y-3 rounded-[20px] bg-[#F5E8DA] p-5">
                            <p class="text-sm font-semibold text-[#8B5E3C]">Tracking number</p>
                            <p class="text-sm text-[#333333]">{{ $order->tracking_number }}</p>
                            <a href="https://www.google.com/search?q={{ urlencode($order->tracking_number) }}" target="_blank" class="inline-flex text-sm font-semibold text-[#8B5E3C] hover:text-[#6f4c32]">Open tracking link</a>
                        </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</x-app-layout>
