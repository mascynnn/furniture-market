@extends('layouts.app')
@section('title', 'CasaForma — Mabel Furniture Pilihan')

@push('styles')
<style>
/* ── Hero ─── */
.hero {
    min-height: 100vh;
    display: grid;
    grid-template-columns: 1fr 1fr;
    position: relative;
    overflow: hidden;
}
.hero__text {
    display: flex; flex-direction: column; justify-content: center;
    padding: 80px 64px 80px 48px;
    position: relative; z-index: 2;
}
.hero__eyebrow {
    font-size: 10px; letter-spacing: 0.24em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 24px;
    display: flex; align-items: center; gap: 12px;
}
.hero__eyebrow::before {
    content: ''; width: 36px; height: 1px; background: var(--gold);
}
.hero__title {
    font-family: var(--font-display);
    font-size: clamp(44px, 6vw, 86px);
    font-weight: 300;
    line-height: 1.08;
    color: var(--cream);
    margin-bottom: 28px;
}
.hero__title em {
    font-style: italic; color: var(--gold);
}
.hero__desc {
    font-size: 15px; color: var(--text-muted);
    line-height: 1.75; max-width: 420px;
    margin-bottom: 48px;
}
.hero__cta { display: flex; gap: 16px; flex-wrap: wrap; }
.hero__stat {
    display: flex; gap: 40px;
    margin-top: 64px;
    padding-top: 40px;
    border-top: 1px solid var(--border);
}
.hero__stat-item {}
.hero__stat-num {
    font-family: var(--font-display);
    font-size: 36px; font-weight: 300;
    color: var(--cream);
}
.hero__stat-label {
    font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--text-muted); margin-top: 4px;
}

.hero__visual {
    position: relative; overflow: hidden; background: #0a0908;
}
.hero__visual img {
    width: 100%; height: 100%;
    object-fit: cover; opacity: 0.7;
    transition: transform 8s ease;
}
.hero__visual:hover img { transform: scale(1.04); }
.hero__visual-badge {
    position: absolute; bottom: 40px; left: 40px;
    background: rgba(13,12,10,0.88);
    border: 1px solid var(--border);
    padding: 18px 24px;
    backdrop-filter: blur(8px);
}
.hero__visual-badge p:first-child {
    font-size: 10px; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 6px;
}
.hero__visual-badge p:last-child {
    font-family: var(--font-display);
    font-size: 20px; color: var(--cream);
}

/* ── Section: Katalog Unggulan ─── */
.featured { padding: 96px 0; }
.featured__header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 48px;
}
.featured__grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

/* ── Section: Inspirasi ─── */
.inspiration {
    padding: 0 0 96px;
}
.inspiration__inner {
    position: relative;
    min-height: 480px;
    overflow: hidden;
    display: flex; align-items: flex-end;
    background: #0a0908;
}
.inspiration__bg {
    position: absolute; inset: 0;
    width: 100%; height: 100%; object-fit: cover; opacity: 0.4;
}
.inspiration__content {
    position: relative; z-index: 2;
    padding: 64px;
    max-width: 560px;
}
.inspiration__content h2 {
    font-family: var(--font-display);
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 300; line-height: 1.15;
    color: var(--cream); margin-bottom: 16px;
}
.inspiration__content h2 em { font-style: italic; color: var(--gold); }
.inspiration__content p {
    font-size: 14px; color: var(--cream-dim); line-height: 1.75;
    margin-bottom: 32px;
}

/* ── Section: Seller CTA ─── */
.seller-cta {
    padding: 80px 0;
    border-top: 1px solid var(--border);
    text-align: center;
}
.seller-cta h2 {
    font-family: var(--font-display);
    font-size: clamp(28px, 4vw, 48px);
    font-weight: 300; color: var(--cream);
    margin-bottom: 16px;
}
.seller-cta h2 em { font-style: italic; color: var(--gold); }
.seller-cta p {
    font-size: 14px; color: var(--text-muted);
    max-width: 480px; margin: 0 auto 36px; line-height: 1.75;
}
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="hero">
    <div class="hero__text container" style="max-width:none;padding:80px 64px 80px 48px">
        <p class="hero__eyebrow">Furniture Berkualitas Tinggi</p>
        <h1 class="hero__title">
            Keanggunan<br>yang <em>Abadi</em><br>di Rumah Anda
        </h1>
        <p class="hero__desc">
            CasaForma menghadirkan koleksi mabel pilihan yang memadukan estetika tinggi
            dengan fungsionalitas sempurna — untuk setiap ruang, setiap momen.
        </p>
        <div class="hero__cta">
            <a href="{{ route('products.index') }}" class="btn btn-primary">Jelajahi Katalog</a>
            <a href="{{ route('register') }}" class="btn btn-ghost">Mulai Berjualan</a>
        </div>
        <div class="hero__stat">
            <div class="hero__stat-item">
                <p class="hero__stat-num">500+</p>
                <p class="hero__stat-label">Produk Pilihan</p>
            </div>
            <div class="hero__stat-item">
                <p class="hero__stat-num">120+</p>
                <p class="hero__stat-label">Seller Terpercaya</p>
            </div>
            <div class="hero__stat-item">
                <p class="hero__stat-num">4.8★</p>
                <p class="hero__stat-label">Rating Rata-rata</p>
            </div>
        </div>
    </div>

    <div class="hero__visual">
        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=1200&auto=format&fit=crop"
             alt="Furniture Elegan CasaForma">
        <div class="hero__visual-badge">
            <p>Koleksi Terbaru</p>
            <p>Autumn Collection 2024</p>
        </div>
    </div>
</section>

{{-- ═══ KATALOG UNGGULAN ═══ --}}
<section class="featured">
    <div class="container">
        <div class="featured__header">
            <div>
                <p class="section-label">Koleksi Terpilih</p>
                <h2 class="section-title">Produk <em>Unggulan</em></h2>
            </div>
            <a href="{{ route('products.index') }}" class="btn btn-ghost">Lihat Semua →</a>
        </div>

        @php
            $featured = \App\Models\Product::with('primaryImage')
                ->active()
                ->orderByDesc('average_rating')
                ->limit(4)
                ->get();
        @endphp

        <div class="featured__grid">
            @foreach($featured as $product)
                <article class="product-card">
                    <div class="product-card__img-wrap">
                        <img src="{{ $product->main_image_url }}"
                             alt="{{ $product->name }}"
                             class="product-card__img" loading="lazy">
                        @if($product->condition === 'new')
                            <span class="product-card__badge">Baru</span>
                        @endif
                    </div>
                    <div class="product-card__body">
                        <p class="product-card__category">{{ ucfirst($product->category) }}</p>
                        <h3 class="product-card__name">
                            <a href="{{ route('products.show', $product->slug) }}" style="color:inherit">{{ $product->name }}</a>
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
                            <svg viewBox="0 0 24 24"
                                 fill="{{ auth()->user()->hasWishlisted($product->id) ? 'currentColor' : 'none' }}"
                                 stroke="currentColor" stroke-width="1.5">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                        @endauth
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>

{{-- ═══ INSPIRASI ═══ --}}
<section class="inspiration">
    <div class="container">
        <div class="inspiration__inner">
            <img class="inspiration__bg"
                 src="https://images.unsplash.com/photo-1631679706909-1844bbd07221?w=1400&auto=format&fit=crop"
                 alt="Ruang Inspirasi">
            <div class="inspiration__content">
                <p class="section-label">Ruang Inspirasi</p>
                <h2>Harmoni di <em>Setiap<br>Sudut Rumah</em></h2>
                <p>Temukan inspirasi dekorasi dan tata ruang dari para desainer interior pilihan CasaForma.</p>
                <a href="{{ route('products.index') }}" class="btn btn-primary">Mulai Berbelanja</a>
            </div>
        </div>
    </div>
</section>

{{-- ═══ SELLER CTA ═══ --}}
@guest
<section class="seller-cta">
    <div class="container">
        <p class="section-label">Bergabung sebagai Seller</p>
        <h2>Jual Karya Terbaik<br>Anda di <em>CasaForma</em></h2>
        <p>Ribuan pembeli menanti produk furniture berkualitas Anda. Mulai berjualan hari ini, gratis.</p>
        <a href="{{ route('register') }}" class="btn btn-primary">Daftar sebagai Seller</a>
    </div>
</section>
@endguest

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
