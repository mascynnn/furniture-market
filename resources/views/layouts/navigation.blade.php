<nav class="bg-white/95 backdrop-blur sticky top-0 z-30 border-b border-[#E6E0D4] shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 lg:px-8">
        <a href="{{ route('home') }}" class="flex items-center gap-3 text-wood font-semibold text-lg">
            <span class="inline-flex h-10 w-10 items-center justify-center rounded-2xl bg-wood text-white shadow-card">F</span>
            <span>Furnivo</span>
        </a>

        <form method="GET" action="{{ route('products.index') }}" class="hidden lg:flex items-center gap-3 flex-1 max-w-2xl mx-6">
            <label for="search" class="sr-only">Search furniture</label>
            <input
                id="search"
                name="search"
                type="search"
                value="{{ request('search') }}"
                placeholder="Search handcrafted furniture"
                class="form-control w-full"
            />
            <button type="submit" class="btn-primary">Search</button>
        </form>

        <div class="flex items-center gap-3">
            <a href="{{ route('products.index') }}" class="text-sm text-[#5A4A3A] hover:text-wood">Explore</a>
            @auth
                @if(auth()->user()->role === 'seller')
                    <a href="{{ route('seller.dashboard') }}" class="text-sm text-[#5A4A3A] hover:text-wood">Seller</a>
                @elseif(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-[#5A4A3A] hover:text-wood">Admin</a>
                @else
                    <a href="{{ route('buyer.orders') }}" class="text-sm text-[#5A4A3A] hover:text-wood">Orders</a>
                @endif
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="text-sm text-[#5A4A3A] hover:text-wood">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-[#5A4A3A] hover:text-wood">Login</a>
                <a href="{{ route('register') }}" class="btn-primary">Register</a>
            @endauth
        </div>
    </div>
</nav>
