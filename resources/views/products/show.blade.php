@extends('layouts.app')
@section('title', $product->name . ' — CasaForma')

@push('styles')
<style>
/* ── Gallery ─── */
.product-detail { padding: 56px 0; }
.product-layout {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 72px;
    align-items: start;
}
.gallery { position: sticky; top: 96px; }
.gallery__main {
    width: 100%; aspect-ratio: 4/3;
    object-fit: cover;
    border: 1px solid var(--border);
    transition: opacity 0.3s;
    background: var(--bg-card);
}
.gallery__thumbs {
    display: flex; gap: 8px; margin-top: 12px;
}
.gallery__thumb {
    width: 72px; height: 72px;
    object-fit: cover;
    cursor: pointer;
    border: 1px solid var(--border);
    opacity: 0.55;
    transition: var(--transition);
}
.gallery__thumb:hover,
.gallery__thumb.active {
    opacity: 1;
    border-color: var(--gold);
}

/* ── Product Info ─── */
.product-info {}
.product-info__category {
    font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase; color: var(--gold);
    margin-bottom: 12px;
}
.product-info__name {
    font-family: var(--font-display);
    font-size: clamp(28px, 3.5vw, 44px);
    font-weight: 400; line-height: 1.2;
    color: var(--cream);
    margin-bottom: 20px;
}
.product-info__rating {
    display: flex; align-items: center; gap: 12px;
    margin-bottom: 24px;
}
.product-info__price {
    font-family: var(--font-display);
    font-size: 36px; font-weight: 500;
    color: var(--gold);
    margin-bottom: 28px;
}
.product-info__stock {
    font-size: 12px; letter-spacing: 0.08em; color: var(--text-muted);
    margin-bottom: 32px;
}
.product-info__stock span { color: var(--cream); }

/* Specs table */
.specs {
    border: 1px solid var(--border);
    margin-bottom: 32px;
}
.spec-row {
    display: grid; grid-template-columns: 140px 1fr;
    border-bottom: 1px solid var(--border);
}
.spec-row:last-child { border-bottom: none; }
.spec-label, .spec-value {
    padding: 12px 16px;
    font-size: 13px;
}
.spec-label {
    color: var(--text-muted);
    font-size: 10px; letter-spacing: 0.12em; text-transform: uppercase;
    border-right: 1px solid var(--border);
    background: rgba(255,255,255,0.02);
    display: flex; align-items: center;
}
.spec-value { color: var(--cream); }

/* Action buttons */
.product-actions {
    display: flex; gap: 12px; margin-bottom: 20px;
}
.product-actions .btn { flex: 1; justify-content: center; }

.wishlist-action {
    width: 52px; height: 52px;
    border: 1px solid var(--border);
    background: none;
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    color: var(--text-muted);
    transition: var(--transition);
    flex-shrink: 0;
}
.wishlist-action:hover, .wishlist-action.active {
    border-color: #e57d6d;
    color: #e57d6d;
}
.wishlist-action svg { width: 22px; height: 22px; }

/* Description */
.product-desc {
    padding-top: 32px;
    border-top: 1px solid var(--border);
}
.product-desc h4 {
    font-size: 10px; letter-spacing: 0.18em; text-transform: uppercase;
    color: var(--gold); margin-bottom: 12px;
}
.product-desc p {
    font-size: 14px; line-height: 1.8; color: var(--text-muted);
}

/* ── Reviews Section ─── */
.reviews-section {
    padding: 72px 0;
    border-top: 1px solid var(--border);
}
.reviews-header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 48px;
}
.reviews-summary {
    display: flex; align-items: center; gap: 32px;
}
.rating-big {
    font-family: var(--font-display);
    font-size: 72px; font-weight: 300;
    color: var(--gold); line-height: 1;
}
.rating-detail {}
.rating-bar {
    display: flex; align-items: center; gap: 10px; margin-bottom: 6px;
}
.rating-bar__label {
    font-size: 11px; color: var(--text-muted); width: 16px; text-align: right;
}
.rating-bar__track {
    width: 140px; height: 4px;
    background: var(--border); border-radius: 2px; overflow: hidden;
}
.rating-bar__fill {
    height: 100%; background: var(--gold); border-radius: 2px;
}
.rating-bar__count { font-size: 11px; color: var(--text-muted); }

/* Review cards */
.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}
.review-card {
    background: var(--bg-card);
    border: 1px solid var(--border);
    padding: 24px;
}
.review-card__header {
    display: flex; justify-content: space-between; align-items: flex-start;
    margin-bottom: 12px;
}
.review-card__user { display: flex; align-items: center; gap: 12px; }
.review-card__avatar {
    width: 36px; height: 36px; border-radius: 50%;
    background: var(--gold);
    display: flex; align-items: center; justify-content: center;
    font-family: var(--font-display); font-size: 16px; color: var(--bg);
}
.review-card__name { font-size: 13px; color: var(--cream); }
.review-card__date { font-size: 11px; color: var(--text-muted); }
.review-card__stars { display: flex; gap: 2px; }
.review-card__stars .star { font-size: 13px; }
.review-card__comment { font-size: 13px; color: var(--text-muted); line-height: 1.7; }

/* Write review form */
.review-form-wrap {
    background: var(--bg-card);
    border: 1px solid var(--border);
    padding: 36px;
    max-width: 600px;
}
.review-form-wrap h3 {
    font-family: var(--font-display);
    font-size: 26px; font-weight: 400;
    color: var(--cream); margin-bottom: 24px;
}
.star-rating { justify-content: flex-start; }

/* Related */
.related-section { padding: 64px 0; }
.related-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}
</style>
@endpush

@section('content')
<div class="product-detail">
    <div class="container">
        {{-- Breadcrumb --}}
        <nav style="font-size:12px;color:var(--text-muted);margin-bottom:40px;letter-spacing:0.06em">
            <a href="{{ route('home') }}" style="color:var(--text-muted);transition:color 0.2s" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-muted)'">Beranda</a>
            <span style="margin:0 10px;color:var(--border)">—</span>
            <a href="{{ route('products.index') }}" style="color:var(--text-muted);transition:color 0.2s" onmouseover="this.style.color='var(--gold)'" onmouseout="this.style.color='var(--text-muted)'">Katalog</a>
            <span style="margin:0 10px;color:var(--border)">—</span>
            <span style="color:var(--cream)">{{ $product->name }}</span>
        </nav>

        <div class="product-layout">
            {{-- Gallery --}}
            <div class="gallery">
                <img id="main-img"
                     class="gallery__main"
                     src="{{ $product->main_image_url }}"
                     alt="{{ $product->name }}">

                @if($product->images->count() > 1)
                    <div class="gallery__thumbs">
                        @foreach($product->images as $img)
                            <img src="{{ $img->url }}"
                                 alt=""
                                 class="gallery__thumb {{ $loop->first ? 'active' : '' }}"
                                 onclick="switchImage(this, '{{ $img->url }}')">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Info --}}
            <div class="product-info">
                <p class="product-info__category">{{ ucfirst($product->category) }} — {{ $product->seller->name }}</p>
                <h1 class="product-info__name">{{ $product->name }}</h1>

                <div class="product-info__rating">
                    <div class="stars">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= round($product->average_rating) ? '' : 'empty' }}">★</span>
                        @endfor
                    </div>
                    <span style="font-size:13px;color:var(--text-muted)">
                        {{ number_format($product->average_rating, 1) }} ({{ $product->total_reviews }} ulasan)
                    </span>
                </div>

                <p class="product-info__price">{{ $product->formatted_price }}</p>

                <p class="product-info__stock">
                    Stok: <span>{{ $product->stock > 0 ? $product->stock . ' tersedia' : 'Habis' }}</span>
                    &nbsp;·&nbsp; Kondisi: <span>{{ $product->condition === 'new' ? 'Baru' : 'Bekas' }}</span>
                </p>

                {{-- Specs --}}
                <div class="specs">
                    @if($product->material)
                    <div class="spec-row">
                        <span class="spec-label">Material</span>
                        <span class="spec-value">{{ $product->material }}</span>
                    </div>
                    @endif
                    @if($product->dimension)
                    <div class="spec-row">
                        <span class="spec-label">Dimensi</span>
                        <span class="spec-value">{{ $product->dimension }}</span>
                    </div>
                    @endif
                    @if($product->color)
                    <div class="spec-row">
                        <span class="spec-label">Warna</span>
                        <span class="spec-value">{{ $product->color }}</span>
                    </div>
                    @endif
                </div>

                {{-- Actions --}}
                @if($product->stock > 0)
                    <div class="product-actions">
                        {{-- Tombol Beli / Keranjang dikerjakan Najwa --}}
                        <a href="{{ route('cart.add', $product->id) }}" class="btn btn-primary">
                            <svg style="width:16px;height:16px" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                            </svg>
                            Tambah ke Keranjang
                        </a>

                        @auth
                        <button class="wishlist-action {{ $isWishlisted ? 'active' : '' }}"
                                id="wishlist-btn"
                                onclick="toggleWishlistDetail({{ $product->id }})">
                            <svg viewBox="0 0 24 24"
                                 fill="{{ $isWishlisted ? 'currentColor' : 'none' }}"
                                 stroke="currentColor" stroke-width="1.5">
                                <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </button>
                        @endauth
                    </div>
                @else
                    <div class="btn btn-ghost" style="width:100%;justify-content:center;opacity:0.5;cursor:not-allowed">Stok Habis</div>
                @endif

                {{-- Description --}}
                <div class="product-desc">
                    <h4>Deskripsi Produk</h4>
                    <p>{{ $product->description }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ═══ REVIEWS SECTION ═══ --}}
<section class="reviews-section">
    <div class="container">
        <div class="reviews-header">
            <div>
                <p class="section-label">Ulasan Pembeli</p>
                <h2 class="section-title">Suara dari <em>Mereka</em></h2>
            </div>
        </div>

        {{-- Rating Summary --}}
        <div class="reviews-summary" style="margin-bottom:48px">
            <div class="rating-big">{{ number_format($product->average_rating, 1) }}</div>
            <div class="rating-detail">
                <div class="stars" style="margin-bottom:8px">
                    @for($i = 1; $i <= 5; $i++)
                        <span class="star" style="font-size:20px;{{ $i <= round($product->average_rating) ? '' : 'color:var(--border)' }}">★</span>
                    @endfor
                </div>
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:16px">{{ $product->total_reviews }} ulasan</p>
                @foreach($ratingDistribution as $stars => $count)
                    <div class="rating-bar">
                        <span class="rating-bar__label">{{ $stars }}</span>
                        <div class="rating-bar__track">
                            <div class="rating-bar__fill"
                                 style="width:{{ $product->total_reviews > 0 ? ($count / $product->total_reviews * 100) : 0 }}%"></div>
                        </div>
                        <span class="rating-bar__count">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Review Cards --}}
        @if($product->reviews->count() > 0)
            <div class="reviews-grid">
                @foreach($product->reviews as $review)
                    <div class="review-card">
                        <div class="review-card__header">
                            <div class="review-card__user">
                                <div class="review-card__avatar">
                                    {{ mb_strtoupper(mb_substr($review->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="review-card__name">{{ $review->user->name }}</p>
                                    <p class="review-card__date">{{ $review->created_at->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="review-card__stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="star {{ $i <= $review->rating ? '' : 'empty' }}">★</span>
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                            <p class="review-card__comment">"{{ $review->comment }}"</p>
                        @endif

                        {{-- Hapus review milik sendiri --}}
                        @if(auth()->check() && auth()->id() === $review->user_id)
                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" style="margin-top:16px">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger" style="padding:6px 14px;font-size:10px">Hapus Ulasan</button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
            @if($product->reviews->count() > 6)
                <a href="#all-reviews" class="btn btn-ghost" style="margin:0 auto;display:flex;width:fit-content">Lihat Semua Ulasan</a>
            @endif
        @else
            <p style="color:var(--text-muted);font-size:14px">Belum ada ulasan untuk produk ini.</p>
        @endif

        {{-- Write Review Form --}}
        @auth
            @if(!$userReview)
                <div class="review-form-wrap" style="margin-top:48px">
                    <h3>Tulis <em style="color:var(--gold)">Ulasan</em></h3>
                    <div class="divider"></div>
                    <form action="{{ route('reviews.store', $product->id) }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label">Rating Bintang</label>
                            <div class="star-rating" id="star-rating">
                                @for($i = 5; $i >= 1; $i--)
                                    <input type="radio" name="rating" id="star{{ $i }}" value="{{ $i }}"
                                           {{ old('rating') == $i ? 'checked' : '' }}>
                                    <label for="star{{ $i }}" title="{{ $i }} Bintang">★</label>
                                @endfor
                            </div>
                            @error('rating')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <div class="form-group">
                            <label for="comment" class="form-label">Komentar <span style="color:var(--text-muted)">(opsional)</span></label>
                            <textarea id="comment" name="comment" rows="4"
                                      class="form-control"
                                      placeholder="Ceritakan pengalaman Anda dengan produk ini...">{{ old('comment') }}</textarea>
                            @error('comment')<p class="form-error">{{ $message }}</p>@enderror
                        </div>

                        <button type="submit" class="btn btn-outline">Kirim Ulasan</button>
                    </form>
                </div>
            @else
                <div style="margin-top:48px;padding:20px 24px;border:1px solid var(--border);display:inline-block">
                    <p style="font-size:12px;color:var(--gold);letter-spacing:0.1em;text-transform:uppercase">Ulasan Anda</p>
                    <div class="stars" style="margin-top:8px">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star {{ $i <= $userReview->rating ? '' : 'empty' }}">★</span>
                        @endfor
                    </div>
                    @if($userReview->comment)
                        <p style="font-size:13px;color:var(--text-muted);margin-top:8px">"{{ $userReview->comment }}"</p>
                    @endif
                </div>
            @endif
        @else
            <div style="margin-top:48px;padding:24px;border:1px solid var(--border);text-align:center">
                <p style="font-size:14px;color:var(--text-muted);margin-bottom:16px">
                    <a href="{{ route('login') }}" style="color:var(--gold)">Masuk</a> untuk menulis ulasan
                </p>
            </div>
        @endauth
    </div>
</section>

{{-- ═══ RELATED PRODUCTS ═══ --}}
@if($related->count() > 0)
<section class="related-section">
    <div class="container">
        <p class="section-label">Koleksi Serupa</p>
        <h2 class="section-title" style="margin-bottom:40px">Sempurnakan <em>Ruang Anda</em></h2>
        <div class="related-grid">
            @foreach($related as $item)
                <article class="product-card">
                    <div class="product-card__img-wrap">
                        <img src="{{ $item->main_image_url }}" alt="{{ $item->name }}" class="product-card__img" loading="lazy">
                    </div>
                    <div class="product-card__body">
                        <p class="product-card__category">{{ ucfirst($item->category) }}</p>
                        <h3 class="product-card__name">
                            <a href="{{ route('products.show', $item->slug) }}" style="color:inherit">{{ $item->name }}</a>
                        </h3>
                        <p class="product-card__price">{{ $item->formatted_price }}</p>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection

@push('scripts')
<script>
function switchImage(thumb, url) {
    document.getElementById('main-img').src = url;
    document.querySelectorAll('.gallery__thumb').forEach(t => t.classList.remove('active'));
    thumb.classList.add('active');
}

function toggleWishlistDetail(productId) {
    @if(auth()->check())
    const btn = document.getElementById('wishlist-btn');
    const svg = btn.querySelector('svg');

    fetch(`/wishlist/${productId}/toggle`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.wishlisted) {
            btn.classList.add('active');
            svg.setAttribute('fill', 'currentColor');
        } else {
            btn.classList.remove('active');
            svg.setAttribute('fill', 'none');
        }
    });
    @else
    window.location = '{{ route("login") }}';
    @endif
}
</script>
@endpush
