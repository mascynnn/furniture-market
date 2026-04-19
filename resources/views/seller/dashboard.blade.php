<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-8">
        <div class="flex flex-col gap-3 md:flex-row md:items-end md:justify-between">
            <div>
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Seller dashboard</p>
                <h1 class="text-3xl font-semibold text-[#333333]">Welcome back, {{ $seller->name }}</h1>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('seller.products.index') }}" class="btn-primary">Manage products</a>
                <a href="{{ route('seller.orders.index') }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] hover:border-wood hover:text-wood">View orders</a>
            </div>
        </div>

        @unless($seller->is_verified)
            <div class="rounded-[28px] bg-[#FFF3E0] p-6 text-[#8B5E3C] shadow-soft">
                <p class="font-semibold">Seller account pending verification</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Your account is waiting for admin approval. You can add products now, but they will be visible once verified.</p>
            </div>
        @endunless

        <div class="grid gap-6 md:grid-cols-3">
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Products</p>
                <p class="mt-4 text-3xl font-semibold text-[#333333]">{{ $products }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Total items in your shop.</p>
            </div>
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Orders</p>
                <p class="mt-4 text-3xl font-semibold text-[#333333]">{{ $orders }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Incoming orders for your products.</p>
            </div>
            <div class="card-surface p-6">
                <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Status</p>
                <p class="mt-4 text-lg font-semibold text-[#333333]">{{ $seller->is_verified ? 'Verified seller' : 'Awaiting approval' }}</p>
                <p class="mt-2 text-sm text-[#5A4A3A]">Your store will open when verified by admin.</p>
            </div>
        </div>
    </div>
</x-app-layout>
