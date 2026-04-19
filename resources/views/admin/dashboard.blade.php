<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Admin panel</p>
                <h1 class="text-3xl font-semibold text-[#333333]">System overview</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn-primary">Browse marketplace</a>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-3">
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Pending sellers</p>
                <p class="mt-4 text-3xl font-semibold text-[#333333]">{{ $pendingSellers->count() }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Local craftsmen awaiting approval.</p>
            </div>
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Products</p>
                <p class="mt-4 text-3xl font-semibold text-[#333333]">{{ $products->total() }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Active items in the catalog.</p>
            </div>
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Recent orders</p>
                <p class="mt-4 text-3xl font-semibold text-[#333333]">{{ $orders->count() }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Latest buyer transactions.</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="card-surface p-6">
                <h2 class="text-xl font-semibold text-[#333333]">Seller verification queue</h2>
                <div class="mt-6 space-y-4">
                    @forelse($pendingSellers as $seller)
                        <div class="rounded-[20px] border border-[#E9E5DD] p-5">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold text-[#333333]">{{ $seller->name }}</p>
                                    <p class="text-sm text-[#5A4A3A]">{{ $seller->email }}</p>
                                </div>
                                <form action="{{ route('admin.sellers.verify', $seller) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="rounded-xl bg-[#F5E8DA] px-4 py-3 text-sm font-semibold text-[#8B5E3C]">Verify seller</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-[#5A4A3A]">No seller requests waiting.</p>
                    @endforelse
                </div>
            </section>

            <section class="card-surface p-6">
                <h2 class="text-xl font-semibold text-[#333333]">Recent orders</h2>
                <div class="mt-6 space-y-4">
                    @foreach($orders as $order)
                        <div class="rounded-[20px] border border-[#E9E5DD] p-5">
                            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="font-semibold text-[#333333]">Order #{{ $order->id }}</p>
                                    <p class="text-sm text-[#5A4A3A]">{{ $order->user->name }} — Rp{{ number_format($order->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="flex flex-wrap gap-3">
                                    <span class="badge-wood">{{ ucfirst($order->payment_status) }}</span>
                                    <form action="{{ route('admin.orders.confirm-payment', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="rounded-xl bg-[#F5E8DA] px-4 py-3 text-sm font-semibold text-[#8B5E3C]">Mark paid</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>

        <section class="card-surface p-6">
            <h2 class="text-xl font-semibold text-[#333333]">Product moderation</h2>
            <div class="mt-6 grid gap-4">
                @foreach($products as $product)
                    <div class="rounded-[20px] border border-[#E9E5DD] p-5 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="font-semibold text-[#333333]">{{ $product->name }}</p>
                            <p class="text-sm text-[#5A4A3A]">Sold by {{ $product->user->name }}</p>
                        </div>
                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="rounded-xl bg-[#F5E8DA] px-4 py-3 text-sm font-semibold text-[#8B5E3C]">Remove</button>
                        </form>
                    </div>
                @endforeach
            </div>

            <div class="mt-6 flex justify-center">
                {{ $products->links() }}
            </div>
        </section>
    </div>
</x-app-layout>
