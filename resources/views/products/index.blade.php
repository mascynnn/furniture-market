@extends('layouts.app')
@section('title', 'Katalog Produk — CasaForma')

@push('styles')
<style>
/* ── Hero Katalog ─── */
.catalog-hero {
    background: var(--bg-card);
    border-bottom: 1px solid var(--border);
    padding: 64px 0 48px;
}
.catalog-hero__inner {
    display: flex; justify-content: space-between; align-items: flex-end;
    flex-wrap: wrap; gap: 24px;
}
.catalog-hero h1 {
    font-family: var(--font-display);
    font-size: clamp(36px, 5vw, 64px);
    font-weight: 300; line-height: 1.1;
    color: var(--cream);
}
.catalog-hero h1 em { font-style: italic; color: var(--gold); }
.catalog-hero__meta {
    font-size: 13px; color: var(--text-muted);
}

/* ── Filter Bar ─── */
.filter-bar {
    background: rgba(20,18,16,0.8);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    position: sticky; top: 68px; z-index: 50;
    padding: 0;
}
.filter-bar__inner {
    display: flex; align-items: stretch; gap: 0;
    overflow-x: auto;
}
.filter-pill {
    padding: 16px 22px;
    font-size: 11px; letter-spacing: 0.12em; text-transform: uppercase;
    color: var(--text-muted);
    white-space: nowrap;
    border: none; background: none;
    cursor: pointer;
    border-bottom: 2px solid transparent;
    transition: var(--transition);
}
.filter-pill:hover { color: var(--cream); }
.filter-pill.active {
    color: var(--gold);
    border-bottom-color: var(--gold);
}
.filter-bar__sep { width: 1px; background: var(--border); margin: 10px 0; }

/* Sort select */
.sort-wrap {
    margin-left: auto;
    padding: 0 24px;
    display: flex; align-items: center; gap: 10px;
    border-left: 1px solid var(--border);
}
.sort-wrap select {
    background: none; border: none;
    font-size: 11px; letter-spacing: 0.1em; text-transform: uppercase;
    color: var(--text-muted); cursor: pointer; outline: none;
}
.sort-wrap select:focus { color: var(--gold); }

/* ── Grid Produk ─── */
.catalog-body { padding: 56px 0; }
.products-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
}
.products-empty {
    grid-column: 1 / -1;
    text-align: center;
    padding: 96px 0;
}
.products-empty__icon {
    font-size: 48px; margin-bottom: 20px; opacity: 0.3;
}
.products-empty h3 {
    font-family: var(--font-display);
    font-size: 28px; color: var(--cream); margin-bottom: 8px;
}
.products-empty p { color: var(--text-muted); font-size: 14px; }

/* Product card hover reveal */
.product-card__quick {
    position: absolute; bottom: 0; left: 0; right: 0;
    background: rgba(13,12,10,0.92);
    display: flex; gap: 0;
    transform: translateY(100%);
    transition: transform 0.3s ease;
}
.product-card__img-wrap:hover .product-card__quick { transform: translateY(0); }
.product-card__quick a {
    flex: 1; padding: 12px;
    text-align: center;
    font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--cream);
    border-right: 1px solid var(--border);
    transition: var(--transition);
}
.product-card__quick a:last-child { border-right: none; }
.product-card__quick a:hover { color: var(--gold); }
</style>
@endpush

@section('content')
{{-- Hero --}}
<section class="catalog-hero">
    <div class="container">
        <div class="catalog-hero__inner">
            <div>
                <p class="section-label">Koleksi Mabel</p>
                <h1>Temukan <em>Keindahan</em><br>di Setiap Ruang</h1>
            </div>
            <p class="catalog-hero__meta">{{ $products->total() }} produk tersedia</p>
        </div>
    </div>
</section>

{{-- Filter Bar --}}
<div class="filter-bar">
    <div class="container">
        <div class="filter-bar__inner">
            <a href="{{ route('products.index', request()->except('category')) }}"
               class="filter-pill {{ !request('category') ? 'active' : '' }}">Semua</a>

            @foreach($categories as $cat)
                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $cat])) }}"
                   class="filter-pill {{ request('category') == $cat ? 'active' : '' }}">
                    {{ ucfirst($cat) }}
                </a>
            @endforeach

            <div class="sort-wrap">
                <span style="font-size:10px;letter-spacing:0.1em;text-transform:uppercase;color:var(--text-muted)">Urutkan</span>
                <form method="GET" action="{{ route('products.index') }}" id="sort-form">
                    @foreach(request()->except('sort') as $key => $val)
                        <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                    @endforeach
                    <select name="sort" onchange="document.getElementById('sort-form').submit()">
                        <option value="latest"     {{ $sort == 'latest'     ? 'selected' : '' }}>Terbaru</option>
                        <option value="price_asc"  {{ $sort == 'price_asc'  ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price_desc" {{ $sort == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="rating"     {{ $sort == 'rating'     ? 'selected' : '' }}>Rating Terbaik</option>
                    </select>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Grid Produk --}}
<div class="catalog-body">
    <div class="container">
        <div class="products-grid">
            @forelse($products as $product)
                <article class="product-card">
                    <div class="product-card__img-wrap">
                        <img src="{{ $product->main_image_url }}"
                             alt="{{ $product->name }}"
                             class="product-card__img"
                             loading="lazy">

                        @if($product->condition === 'new')
                            <span class="product-card__badge">Baru</span>
                        @endif

                        {{-- Quick actions on hover --}}
                        <div class="product-card__quick">
                            <a href="{{ route('products.show', $product->slug) }}">Lihat Detail</a>
                            @auth
                                <a href="{{ route('wishlist.toggle', $product->id) }}"
                                   onclick="event.preventDefault(); toggleWishlist(this, {{ $product->id }})">
                                   ♡ Simpan
                                </a>
                            @endauth
                        </div>
                    </div>

                    <div class="product-card__body">
                        <p class="product-card__category">{{ ucfirst($product->category) }}</p>
                        <h3 class="product-card__name">
                            <a href="{{ route('products.show', $product->slug) }}"
                               style="color:inherit;transition:color var(--transition)"
                               onmouseover="this.style.color='var(--gold)'"
                               onmouseout="this.style.color='inherit'">
                                {{ $product->name }}
                            </a>
                        </h3>
                        <p class="product-card__price">{{ $product->formatted_price }}</p>
                    </div>

                    <div class="product-card__footer">
                        <div class="stars">
                            @for($i = 1; $i <= 5; $i++)
                                <span class="star {{ $i <= round($product->average_rating) ? '' : 'empty' }}">★</span>
                            @endfor
                            <span style="font-size:11px;color:var(--text-muted);margin-left:6px">({{ $product->total_reviews }})</span>
                        </div>

                        @auth
                            <button class="wishlist-btn {{ auth()->user()->hasWishlisted($product->id) ? 'active' : '' }}"
                                    data-product="{{ $product->id }}"
                                    onclick="toggleWishlist(this, {{ $product->id }})">
                                <svg viewBox="0 0 24 24" fill="{{ auth()->user()->hasWishlisted($product->id) ? 'currentColor' : 'none' }}" stroke="currentColor" stroke-width="1.5">
                                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </button>
                        @endauth
                    </div>
                </article>
            @empty
                <div class="products-empty">
                    <div class="products-empty__icon">🪑</div>
                    <h3>Produk tidak ditemukan</h3>
                    <p>Coba ubah filter atau kata kunci pencarian Anda</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($products->hasPages())
            <div class="pagination">
                {{ $products->onEachSide(1)->links('vendor.pagination.casaforma') }}
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
function toggleWishlist(el, productId) {
    @if(auth()->check())
    fetch(`/wishlist/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        // Update semua tombol wishlist untuk produk ini
        document.querySelectorAll(`[data-product="${productId}"]`).forEach(btn => {
            const svg = btn.querySelector('svg');
            if (data.wishlisted) {
                btn.classList.add('active');
                svg.setAttribute('fill', 'currentColor');
            } else {
                btn.classList.remove('active');
                svg.setAttribute('fill', 'none');
            }
        });
    });
    @else
    window.location = '{{ route("login") }}';
    @endif
}
</script>
@endpush
