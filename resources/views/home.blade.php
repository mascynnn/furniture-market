<x-app-layout>
    <div class="mx-auto max-w-7xl space-y-10">
        <section class="rounded-[28px] bg-white p-8 shadow-soft">
            <div class="grid gap-8 lg:grid-cols-[1.2fr_0.8fr] items-center">
                <div class="space-y-6">
                    <span class="inline-flex rounded-full bg-[#F5E8DA] px-4 py-1 text-sm font-semibold text-[#8B5E3C]">Handmade furniture marketplace</span>
                    <h1 class="max-w-2xl text-4xl font-semibold tracking-tight text-[#333333]">Discover handcrafted furniture from local craftsmen, made for your home.</h1>
                    <p class="max-w-2xl text-base leading-7 text-[#5A4A3A]">Browse carefully selected artisanal furniture with warm design and flexible pre-order options for your next home project.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="{{ route('products.index') }}" class="btn-primary">Shop furniture</a>
                        <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl border border-[#E9E5DD] px-5 py-3 text-sm font-semibold text-[#5A4A3A] transition hover:border-wood hover:text-wood">Join as seller</a>
                    </div>
                </div>
                <div class="rounded-[28px] bg-[#FDFDFC] p-6 shadow-card">
                    <div class="grid gap-4">
                        <div class="rounded-3xl bg-[#F5E8DA] p-5">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#8B5E3C]">Featured craft</p>
                            <p class="mt-3 text-xl font-semibold text-[#333333]">Natural oak dining table</p>
                        </div>
                        <div class="rounded-3xl bg-white p-5 border border-[#E9E5DD]">
                            <p class="text-sm font-medium text-[#5A4A3A]">Pre-order production</p>
                            <p class="mt-2 text-lg font-semibold text-[#333333]">Ready in 7 days with custom finishes.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="grid gap-6 md:grid-cols-3">
            <article class="card-surface p-6">
                <h2 class="text-xl font-semibold text-[#333333]">Warm design system</h2>
                <p class="mt-3 text-sm leading-6 text-[#5A4A3A]">Soft natural colors, clean layout, and premium spacing for a calm shopping experience.</p>
            </article>
            <article class="card-surface p-6">
                <h2 class="text-xl font-semibold text-[#333333]">Verified craftsmen</h2>
                <p class="mt-3 text-sm leading-6 text-[#5A4A3A]">Every seller is reviewed and approved by the Furnivo team for trusted service.</p>
            </article>
            <article class="card-surface p-6">
                <h2 class="text-xl font-semibold text-[#333333]">Manual payment made simple</h2>
                <p class="mt-3 text-sm leading-6 text-[#5A4A3A]">Bank transfer checkout with proof upload and clear order tracking.</p>
            </article>
        </section>

        <section class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Featured collection</p>
                    <h2 class="text-3xl font-semibold text-[#333333]">Fresh arrivals from local artisans</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-[#8B5E3C] hover:text-[#6f4c32]">View all products</a>
            </div>
            <div class="grid gap-6 lg:grid-cols-4 md:grid-cols-2">
                @foreach($featured as $product)
                    <article class="card-surface overflow-hidden">
                        <div class="h-56 bg-[#F5F1EB] object-cover">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1519710164239-da123dc03ef4?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-lg font-semibold text-[#333333]">{{ $product->name }}</h3>
                                <span class="badge-wood">{{ $product->is_preorder ? 'Pre-order' : 'Handmade' }}</span>
                            </div>
                            <p class="mt-3 text-sm text-[#5A4A3A] line-clamp-3">{{ Str::limit($product->description, 80) }}</p>
                            <div class="mt-5 flex items-center justify-between text-sm text-[#5A4A3A]">
                                <span>{{ $product->material ?? 'Natural material' }}</span>
                                <span class="font-semibold text-[#8B5E3C]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <section class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <p class="text-sm uppercase tracking-[0.25em] text-[#8B5E3C]">Latest products</p>
                    <h2 class="text-3xl font-semibold text-[#333333]">New handmade furniture</h2>
                </div>
                <a href="{{ route('products.index') }}" class="text-sm font-semibold text-[#8B5E3C] hover:text-[#6f4c32]">Shop latest</a>
            </div>
            <div class="grid gap-6 lg:grid-cols-4 md:grid-cols-2">
                @foreach($latest as $product)
                    <article class="card-surface overflow-hidden">
                        <div class="h-52 bg-[#F5F1EB]">
                            <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://images.unsplash.com/photo-1493666438817-866a91353ca9?auto=format&fit=crop&w=800&q=60' }}" alt="{{ $product->name }}" class="h-full w-full object-cover" />
                        </div>
                        <div class="p-5">
                            <div class="flex items-center justify-between gap-2">
                                <h3 class="text-lg font-semibold text-[#333333]">{{ $product->name }}</h3>
                                <span class="badge-wood">{{ $product->is_preorder ? 'Pre-order' : 'Handmade' }}</span>
                            </div>
                            <p class="mt-3 text-sm text-[#5A4A3A] line-clamp-3">{{ Str::limit($product->description, 70) }}</p>
                            <div class="mt-5 flex items-center justify-between text-sm text-[#5A4A3A]">
                                <span>{{ $product->material ?? 'Premium' }}</span>
                                <span class="font-semibold text-[#8B5E3C]">Rp{{ number_format($product->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>
    </div>
</x-app-layout>
