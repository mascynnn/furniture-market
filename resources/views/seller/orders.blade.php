<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Seller orders</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Incoming orders</h1>
            </div>
            <a href="{{ route('seller.products.index') }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">Back to products</a>
        </div>

        <div class="space-y-6">
            @forelse($orders as $order)
                <div class="card-surface p-6 shadow-soft">
                    <div class="grid gap-6 lg:grid-cols-[1fr_0.9fr]">
                        <div>
                            <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Order #{{ $order->id }}</p>
                            <p class="mt-3 text-lg font-semibold text-[#333333]">Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                            <p class="mt-1 text-sm text-[#5A4A3A]">{{ $order->created_at->format('d M Y') }}</p>
                        </div>
                        <form action="{{ route('seller.orders.update', $order) }}" method="POST" class="space-y-4 rounded-[24px] bg-[#FDFDFC] p-5 border border-[#E9E5DD]">
                            @csrf
                            <div>
                                <label class="block text-sm font-medium text-[#5A4A3A]">Order status</label>
                                <select name="status" class="form-control mt-2">
                                    <option value="processed" {{ $order->status === 'processed' ? 'selected' : '' }}>Processed</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Shipped</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#5A4A3A]">Courier</label>
                                <input type="text" name="courier" value="{{ old('courier', $order->courier) }}" class="form-control mt-2" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-[#5A4A3A]">Tracking number</label>
                                <input type="text" name="tracking_number" value="{{ old('tracking_number', $order->tracking_number) }}" class="form-control mt-2" />
                            </div>
                            <button type="submit" class="btn-primary w-full">Update order</button>
                        </form>
                    </div>
                    <div class="mt-6 rounded-[20px] border border-[#E9E5DD] p-5">
                        <p class="font-semibold text-[#333333]">Items</p>
                        <ul class="mt-3 space-y-3 text-sm text-[#5A4A3A]">
                            @foreach($order->items as $item)
                                <li>{{ $item->quantity }} × {{ $item->product->name }} — Rp{{ number_format($item->price, 0, ',', '.') }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @empty
                <div class="card-surface p-8 text-center text-[#5A4A3A]">No orders for your products yet.</div>
            @endforelse
        </div>

        <div class="flex justify-center">
            {{ $orders->links() }}
        </div>
    </div>
</x-app-layout>
