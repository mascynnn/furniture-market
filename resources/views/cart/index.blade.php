@extends('layouts.app')

@section('title', 'Keranjang Belanja')

@push('styles')
<style>
    .cart-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    /* Cart Items */
    .cart-items { }
    .cart-item {
        display: grid;
        grid-template-columns: 100px 1fr auto;
        gap: 1.25rem;
        padding: 1.5rem;
        border-bottom: 1px solid var(--border);
        align-items: center;
    }
    .cart-item:last-child { border-bottom: none; }
    .cart-item-img {
        width: 100px;
        height: 80px;
        border-radius: 6px;
        object-fit: cover;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .cart-item-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 6px; }
    .item-cat { font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.08em; color: var(--muted); }
    .item-name {
        font-family: var(--font-display);
        font-size: 1rem;
        color: var(--charcoal);
        margin: 0.2rem 0;
    }
    .item-price { font-size: 0.9rem; color: var(--muted); }

    .qty-control {
        display: flex;
        align-items: center;
        gap: 0;
        border: 1.5px solid var(--border);
        border-radius: 5px;
        overflow: hidden;
        width: fit-content;
    }
    .qty-btn {
        background: none;
        border: none;
        padding: 0.4rem 0.75rem;
        cursor: pointer;
        font-size: 1rem;
        color: var(--charcoal);
        transition: background 0.2s;
    }
    .qty-btn:hover { background: var(--cream); }
    .qty-input {
        border: none;
        border-left: 1.5px solid var(--border);
        border-right: 1.5px solid var(--border);
        width: 48px;
        text-align: center;
        font-family: var(--font-body);
        font-size: 0.9rem;
        padding: 0.4rem 0;
        outline: none;
    }

    .cart-item-actions {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        gap: 0.75rem;
    }
    .item-total {
        font-weight: 600;
        font-size: 1rem;
        color: var(--charcoal);
    }
    .btn-remove {
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.3rem;
        transition: color 0.2s;
        padding: 0;
    }
    .btn-remove:hover { color: var(--danger); }

    /* Summary Card */
    .summary-card {
        position: sticky;
        top: 80px;
    }
    .summary-card .card-body { padding: 1.75rem; }
    .summary-title {
        font-family: var(--font-display);
        font-size: 1.2rem;
        margin-bottom: 1.5rem;
        color: var(--charcoal);
    }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.85rem;
        font-size: 0.9rem;
        color: var(--muted);
    }
    .summary-row.total {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--charcoal);
        border-top: 1px solid var(--border);
        padding-top: 1rem;
        margin-top: 1rem;
        margin-bottom: 1.5rem;
    }
    .summary-row span:last-child { color: var(--charcoal); }
    .summary-row.total span:last-child { color: var(--charcoal); }

    /* Preview mini cart items */
    .mini-item {
        display: flex;
        gap: 0.85rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .mini-item-img {
        width: 52px;
        height: 44px;
        border-radius: 4px;
        object-fit: cover;
        background: var(--cream);
        flex-shrink: 0;
    }
    .mini-item-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
    .mini-item-info { flex: 1; }
    .mini-item-name { font-size: 0.82rem; color: var(--charcoal); font-weight: 500; }
    .mini-item-qty { font-size: 0.78rem; color: var(--muted); }
    .mini-item-price { font-size: 0.85rem; font-weight: 600; color: var(--charcoal); }
    .mini-divider { height: 1px; background: var(--border); margin: 1.25rem 0; }

    .empty-cart {
        text-align: center;
        padding: 5rem 2rem;
        color: var(--muted);
    }
    .empty-cart svg { margin-bottom: 1.5rem; opacity: 0.3; }
    .empty-cart h2 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        color: var(--charcoal);
        margin-bottom: 0.5rem;
    }
    .empty-cart p { margin-bottom: 2rem; }
</style>
@endpush

@section('content')
<h1 class="section-heading">Keranjang Belanja</h1>
<p class="section-subheading">{{ count($cartItems) }} item dalam keranjang Anda</p>

@if(count($cartItems) > 0)
<div class="cart-layout">

    {{-- LEFT: Items --}}
    <div class="card">
        @foreach($cartItems as $id => $item)
        <div class="cart-item" id="cart-item-{{ $id }}">
            <div class="cart-item-img">
                @if(isset($item['thumbnail']))
                    <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="{{ $item['name'] }}">
                @else
                    <svg width="32" height="32" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="opacity:0.3"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                @endif
            </div>

            <div>
                <div class="item-cat">{{ $item['category'] ?? 'Furniture' }}</div>
                <div class="item-name">{{ $item['name'] }}</div>
                <div class="item-price">Rp {{ number_format($item['price'], 0, ',', '.') }} / item</div>
                <form method="POST" action="{{ route('cart.update', $id) }}" style="margin-top:0.75rem;" id="update-form-{{ $id }}">
                    @csrf @method('PATCH')
                    <div class="qty-control">
                        <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, -1)">−</button>
                        <input class="qty-input" type="number" name="quantity" id="qty-{{ $id }}"
                               value="{{ $item['quantity'] }}" min="1" max="99"
                               onchange="document.getElementById('update-form-{{ $id }}').submit()">
                        <button type="button" class="qty-btn" onclick="changeQty({{ $id }}, 1)">+</button>
                    </div>
                </form>
            </div>

            <div class="cart-item-actions">
                <div class="item-total">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                <form method="POST" action="{{ route('cart.remove', $id) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-remove">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="3 6 5 6 21 6"/><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>
                        Hapus
                    </button>
                </form>
            </div>
        </div>
        @endforeach

        {{-- Cart Footer Actions --}}
        <div style="padding:1.25rem 1.5rem; display:flex; justify-content:space-between; align-items:center; border-top: 1px solid var(--border);">
            <a href="{{ route('search.index') }}" class="btn btn-outline">← Lanjut Belanja</a>
            <form method="POST" action="{{ route('cart.clear') }}">
                @csrf @method('DELETE')
                <button type="submit" class="btn btn-outline" style="color:var(--danger); border-color:var(--danger);">Kosongkan Keranjang</button>
            </form>
        </div>
    </div>

    {{-- RIGHT: Summary --}}
    <div class="summary-card">
        <div class="card">
            <div class="card-body">
                <div class="summary-title">Keranjang Belanja</div>

                @foreach($cartItems as $id => $item)
                <div class="mini-item">
                    <div class="mini-item-img">
                        @if(isset($item['thumbnail']))
                            <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="{{ $item['name'] }}">
                        @else
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:0.3;margin:auto"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        @endif
                    </div>
                    <div class="mini-item-info">
                        <div class="mini-item-name">{{ $item['name'] }}</div>
                        <div class="mini-item-qty">× {{ $item['quantity'] }}</div>
                    </div>
                    <div class="mini-item-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                </div>
                @endforeach

                <div class="mini-divider"></div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Pengiriman Sementara</span>
                    <span style="color:var(--muted)">Dihitung saat checkout</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <a href="{{ route('checkout.index') }}" class="btn btn-accent btn-lg" style="width:100%; justify-content:center;">
                    Proses Pembayaran (Checkout)
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <p style="font-size:0.75rem; color:var(--muted); text-align:center; margin-top:0.75rem;">
                    Anda dapat mengganti jumlah atau menghapus item sebelum konfirmasi.
                </p>
            </div>
        </div>
    </div>
</div>

@else
<div class="empty-cart">
    <svg width="80" height="80" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
    <h2>Keranjang Masih Kosong</h2>
    <p>Mulai jelajahi koleksi furniture kami dan temukan yang sempurna untuk rumah Anda.</p>
    <a href="{{ route('search.index') }}" class="btn btn-accent btn-lg">Jelajahi Koleksi</a>
</div>
@endif
@endsection

@push('scripts')
<script>
function changeQty(id, delta) {
    const input = document.getElementById('qty-' + id);
    let val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > 99) val = 99;
    input.value = val;
    document.getElementById('update-form-' + id).submit();
}
</script>
@endpush
