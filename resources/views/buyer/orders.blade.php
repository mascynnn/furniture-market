<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Buyer dashboard</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Your orders</h1>
            </div>
            <a href="{{ route('products.index') }}" class="btn-primary">Continue shopping</a>
        </div>

        @forelse($orders as $order)
            <div class="card-surface p-6 shadow-soft">
                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div>
                        <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Order #{{ $order->id }}</p>
                        <p class="mt-2 text-lg font-semibold text-[#333333]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                        <p class="mt-1 text-sm text-[#5A4A3A]">{{ $order->created_at->format('d M Y') }}</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="badge-wood">{{ ucfirst($order->status) }}</span>
                        <span class="badge-wood">Payment: {{ ucfirst($order->payment_status) }}</span>
                        <span class="badge-wood">Shipping: {{ ucfirst($order->shipping_status) }}</span>
                    </div>
                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ route('buyer.orders.show', $order) }}" class="rounded-xl border border-[#E9E5DD] px-4 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">View details</a>
                        @if($order->tracking_number)
                            <button x-data="{}" x-on:click="navigator.clipboard.writeText('{{ $order->tracking_number }}')" class="rounded-xl bg-[#F5E8DA] px-4 py-3 text-sm font-semibold text-[#8B5E3C]">Copy tracking</button>
                            <a href="https://www.google.com/search?q={{ urlencode($order->tracking_number) }}" target="_blank" class="rounded-xl border border-[#E9E5DD] px-4 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Track shipment</a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="card-surface p-8 text-center text-[#5A4A3A]">No orders yet. Start shopping to create your first order.</div>
        @endforelse

        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
