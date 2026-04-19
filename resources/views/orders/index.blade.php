@extends('layouts.app')

@section('title', 'Pesanan Saya')

@push('styles')
<style>
    .orders-filter {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .status-tab {
        padding: 0.5rem 1.1rem;
        border-radius: 100px;
        font-size: 0.82rem;
        font-weight: 500;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--muted);
        text-decoration: none;
        transition: all 0.2s;
    }
    .status-tab:hover, .status-tab.active {
        background: var(--charcoal);
        color: #fff;
        border-color: var(--charcoal);
    }

    .order-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        margin-bottom: 1rem;
        overflow: hidden;
        transition: box-shadow 0.2s;
    }
    .order-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.06); }
    .order-card-header {
        padding: 1rem 1.5rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-bottom: 1px solid var(--border);
        background: var(--cream);
    }
    .order-number { font-weight: 600; font-size: 0.9rem; color: var(--charcoal); }
    .order-date { font-size: 0.8rem; color: var(--muted); }
    .order-card-body { padding: 1.25rem 1.5rem; }
    .order-preview {
        display: flex;
        gap: 0.85rem;
        align-items: center;
        margin-bottom: 1rem;
    }
    .order-preview-img {
        width: 52px;
        height: 44px;
        border-radius: 4px;
        background: var(--cream);
        object-fit: cover;
    }
    .order-preview-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
    .more-items {
        width: 52px;
        height: 44px;
        border-radius: 4px;
        background: var(--cream);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        color: var(--muted);
        font-weight: 500;
    }
    .order-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--border);
    }
    .order-total { font-weight: 600; color: var(--charcoal); }
</style>
@endpush

@section('content')
<h1 class="section-heading">Pesanan Saya</h1>
<p class="section-subheading">Pantau status pengiriman dan riwayat pembelian Anda</p>

<div class="orders-filter">
    <a href="{{ route('orders.index') }}" class="status-tab {{ !request('status') ? 'active' : '' }}">Semua</a>
    <a href="{{ route('orders.index', ['status' => 'pending']) }}" class="status-tab {{ request('status') == 'pending' ? 'active' : '' }}">Menunggu</a>
    <a href="{{ route('orders.index', ['status' => 'paid']) }}" class="status-tab {{ request('status') == 'paid' ? 'active' : '' }}">Dibayar</a>
    <a href="{{ route('orders.index', ['status' => 'shipped']) }}" class="status-tab {{ request('status') == 'shipped' ? 'active' : '' }}">Dikirim</a>
    <a href="{{ route('orders.index', ['status' => 'delivered']) }}" class="status-tab {{ request('status') == 'delivered' ? 'active' : '' }}">Selesai</a>
    <a href="{{ route('orders.index', ['status' => 'cancelled']) }}" class="status-tab {{ request('status') == 'cancelled' ? 'active' : '' }}">Dibatalkan</a>
</div>

@forelse($orders as $order)
<div class="order-card">
    <div class="order-card-header">
        <div>
            <div class="order-number">#{{ $order->order_number }}</div>
            <div class="order-date">{{ $order->created_at->format('d M Y, H:i') }}</div>
        </div>
        <span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span>
    </div>
    <div class="order-card-body">
        <div class="order-preview">
            @foreach($order->items->take(4) as $item)
            <div class="order-preview-img">
                @if($item->product?->thumbnail)
                    <img src="{{ asset('storage/' . $item->product->thumbnail) }}" alt="{{ $item->product_name }}">
                @else
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:0.3; margin:12px auto; display:block"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                @endif
            </div>
            @endforeach
            @if($order->items->count() > 4)
            <div class="more-items">+{{ $order->items->count() - 4 }}</div>
            @endif
        </div>
        <div style="font-size:0.85rem; color:var(--muted)">
            {{ $order->items->count() }} item
            @if($order->shipping_service) · {{ $order->shipping_service }} @endif
            @if($order->estimated_delivery) · Est. {{ $order->estimated_delivery }} @endif
        </div>
    </div>
    <div class="order-card-footer">
        <div class="order-total">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</div>
        <div style="display:flex; gap:0.5rem;">
            <a href="{{ route('orders.show', $order->id) }}" class="btn btn-outline btn-sm">Detail Pesanan</a>
            @if(in_array($order->status, ['shipped']))
                <a href="{{ route('shipping.track', $order->id) }}" class="btn btn-primary btn-sm">Lacak Kiriman</a>
            @endif
            @if($order->status == 'pending')
                <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-outline btn-sm" style="color:var(--danger)" onclick="return confirm('Batalkan pesanan ini?')">Batalkan</button>
                </form>
            @endif
        </div>
    </div>
</div>
@empty
<div style="text-align:center; padding:4rem 2rem; color:var(--muted);">
    <svg width="64" height="64" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="margin-bottom:1rem; opacity:0.3"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/></svg>
    <h3 style="font-family:var(--font-display); font-size:1.3rem; color:var(--charcoal); margin-bottom:0.5rem">Belum Ada Pesanan</h3>
    <p>Mulai belanja dan pesanan Anda akan muncul di sini.</p>
    <a href="{{ route('search.index') }}" class="btn btn-accent" style="margin-top:1.25rem">Mulai Belanja</a>
</div>
@endforelse

@if($orders->hasPages())
<div style="margin-top:2rem; display:flex; justify-content:center;">
    {{ $orders->withQueryString()->links('vendor.pagination.casaforma') }}
</div>
@endif
@endsection
