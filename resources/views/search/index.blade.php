@extends('layouts.app')

@section('title', 'Pencarian Koleksi')

@push('styles')
<style>
    .search-hero {
        background: var(--cream);
        border-radius: 12px;
        padding: 2.5rem;
        margin-bottom: 2.5rem;
    }
    .search-bar-wrap {
        position: relative;
        max-width: 620px;
        margin-top: 1.25rem;
    }
    .search-bar-wrap input {
        width: 100%;
        padding: 0.85rem 1.25rem 0.85rem 3rem;
        border: 1.5px solid var(--border);
        border-radius: 6px;
        font-family: var(--font-body);
        font-size: 0.95rem;
        background: #fff;
        color: var(--charcoal);
        transition: border-color 0.2s;
        outline: none;
    }
    .search-bar-wrap input:focus { border-color: var(--accent); }
    .search-bar-wrap .search-icon {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--muted);
    }
    .search-bar-wrap button {
        position: absolute;
        right: 0.5rem;
        top: 50%;
        transform: translateY(-50%);
    }

    /* Filters */
    .filter-row {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
        margin-bottom: 2rem;
        align-items: center;
    }
    .filter-chip {
        padding: 0.45rem 1.1rem;
        border-radius: 100px;
        font-size: 0.82rem;
        font-weight: 500;
        cursor: pointer;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--muted);
        transition: all 0.2s;
        text-decoration: none;
    }
    .filter-chip:hover, .filter-chip.active {
        background: var(--charcoal);
        color: #fff;
        border-color: var(--charcoal);
    }

    .filter-select {
        padding: 0.45rem 0.85rem;
        border-radius: 6px;
        border: 1.5px solid var(--border);
        font-family: var(--font-body);
        font-size: 0.82rem;
        color: var(--charcoal);
        background: #fff;
        outline: none;
        cursor: pointer;
    }

    /* Product Grid */
    .products-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.25rem;
        color: var(--muted);
        font-size: 0.875rem;
    }
    .products-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.5rem;
    }
    .product-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.25s, transform 0.25s;
    }
    .product-card:hover {
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    .product-img {
        width: 100%;
        aspect-ratio: 4/3;
        object-fit: cover;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        color: var(--muted);
        font-size: 0.8rem;
    }
    .product-img img { width: 100%; height: 100%; object-fit: cover; }
    .product-body { padding: 1.25rem; }
    .product-cat {
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: var(--muted);
        margin-bottom: 0.4rem;
    }
    .product-name {
        font-family: var(--font-display);
        font-size: 1.05rem;
        color: var(--charcoal);
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }
    .product-desc {
        font-size: 0.82rem;
        color: var(--muted);
        margin-bottom: 1rem;
        line-height: 1.5;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .product-price {
        font-size: 1rem;
        font-weight: 600;
        color: var(--charcoal);
        margin-bottom: 1rem;
    }

    /* Price range filter */
    .price-range {
        display: flex;
        gap: 0.5rem;
        align-items: center;
    }
    .price-range input {
        width: 110px;
        padding: 0.4rem 0.7rem;
        border: 1.5px solid var(--border);
        border-radius: 5px;
        font-size: 0.82rem;
        font-family: var(--font-body);
        outline: none;
    }
    .price-range input:focus { border-color: var(--accent); }

    /* Pagination */
    .pagination-wrap { margin-top: 2.5rem; display: flex; justify-content: center; }
    .pagination { display: flex; gap: 0.4rem; list-style: none; }
    .page-link {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 5px;
        border: 1.5px solid var(--border);
        font-size: 0.875rem;
        color: var(--charcoal);
        text-decoration: none;
        transition: all 0.2s;
    }
    .page-link:hover, .page-link.active {
        background: var(--charcoal);
        color: #fff;
        border-color: var(--charcoal);
    }

    /* Empty state */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--muted);
        grid-column: 1/-1;
    }
    .empty-state svg { margin-bottom: 1rem; opacity: 0.4; }
    .empty-state h3 {
        font-family: var(--font-display);
        font-size: 1.3rem;
        color: var(--charcoal);
        margin-bottom: 0.5rem;
    }

    .filter-divider { height: 24px; width: 1px; background: var(--border); }
</style>
@endpush

@section('content')
<div class="search-hero">
    <h1 class="section-heading">Pencarian Koleksi</h1>
    <p class="section-subheading">Temukan furniture yang dirancang khusus untuk ruang Anda. Setiap potongan membawa cerita kerajinan tangan yang memikat.</p>

    <form method="GET" action="{{ route('search.index') }}" class="search-bar-wrap">
        <svg class="search-icon" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari furniture atau material...">
        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
    </form>
</div>

{{-- Filter Row --}}
<form method="GET" action="{{ route('search.index') }}" id="filterForm">
    @if(request('q'))
        <input type="hidden" name="q" value="{{ request('q') }}">
    @endif

    <div class="filter-row">
        <a href="{{ route('search.index', array_merge(request()->except('category', 'page'), [])) }}"
           class="filter-chip {{ !request('category') ? 'active' : '' }}">Semua</a>

        @foreach($categories as $cat)
        <a href="{{ route('search.index', array_merge(request()->except('category','page'), ['category' => $cat->slug])) }}"
           class="filter-chip {{ request('category') == $cat->slug ? 'active' : '' }}">
            {{ $cat->name }}
        </a>
        @endforeach

        <div class="filter-divider"></div>

        {{-- Material filter --}}
        <select name="material" class="filter-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Semua Material</option>
            @foreach($materials as $mat)
                <option value="{{ $mat }}" {{ request('material') == $mat ? 'selected' : '' }}>{{ $mat }}</option>
            @endforeach
        </select>

        {{-- Sort --}}
        <select name="sort" class="filter-select" onchange="document.getElementById('filterForm').submit()">
            <option value="">Urutkan</option>
            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga: Rendah ke Tinggi</option>
            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga: Tinggi ke Rendah</option>
            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Terpopuler</option>
        </select>

        {{-- Price range --}}
        <div class="price-range">
            <input type="number" name="min_price" placeholder="Harga min" value="{{ request('min_price') }}">
            <span>—</span>
            <input type="number" name="max_price" placeholder="Harga maks" value="{{ request('max_price') }}">
            <button type="submit" class="btn btn-outline btn-sm">Terapkan</button>
        </div>
    </div>
</form>

<div class="products-meta">
    <span>
        @if(request('q'))
            Hasil untuk "<strong>{{ request('q') }}</strong>" —
        @endif
        {{ $products->total() }} produk ditemukan
    </span>
    <span>Halaman {{ $products->currentPage() }} dari {{ $products->lastPage() }}</span>
</div>

<div class="products-grid">
    @forelse($products as $product)
    <div class="product-card">
        <div class="product-img">
            @if($product->thumbnail)
                <img src="{{ asset('storage/' . $product->thumbnail) }}" alt="{{ $product->name }}">
            @else
                <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="opacity:0.3"><rect x="3" y="3" width="18" height="18" rx="2"/><path d="m3 9 4-4 4 4 4-4 4 4"/></svg>
            @endif
        </div>
        <div class="product-body">
            <div class="product-cat">{{ $product->category->name ?? '—' }}</div>
            <div class="product-name">{{ $product->name }}</div>
            <p class="product-desc">{{ $product->description }}</p>
            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
            <div style="display:flex; gap:0.5rem;">
                <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline btn-sm">Detail Koleksi</a>
                <form method="POST" action="{{ route('cart.add') }}">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <input type="hidden" name="quantity" value="1">
                    <button type="submit" class="btn btn-primary btn-sm">+ Keranjang</button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="empty-state">
        <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/><line x1="8" y1="11" x2="14" y2="11"/></svg>
        <h3>Tidak ada produk ditemukan</h3>
        <p>Coba kata kunci yang berbeda atau hapus beberapa filter.</p>
        <a href="{{ route('search.index') }}" class="btn btn-outline" style="margin-top:1rem;">Reset Pencarian</a>
    </div>
    @endforelse
</div>

{{-- Pagination --}}
@if($products->hasPages())
<div class="pagination-wrap">
    {{ $products->withQueryString()->links('vendor.pagination.casaforma') }}
</div>
@endif
@endsection
