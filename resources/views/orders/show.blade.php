@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@push('styles')
<style>
    .order-detail-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 2rem;
        align-items: start;
    }
    .section-card { margin-bottom: 1.25rem; }
    .section-card-title {
        font-weight: 600;
        font-size: 0.9rem;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--cream);
    }
    .info-row {
        display: flex;
        justify-content: space-between;
        padding: 0.7rem 1.5rem;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: var(--muted); }
    .info-row .value { font-weight: 500; text-align: right; max-width: 60%; }
</style>
@endpush

@section('content')
<div style="margin-bottom:1.25rem; display:flex; align-items:center; gap:0.75rem;">
    <a href="{{ route('orders.index') }}" style="color:var(--muted);text-decoration:none;font-size:0.875rem;display:flex;align-items:center;gap:0.3rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Pesanan Saya
    </a>
    <span style="color:var(--border)">/</span>
    <span style="font-size:0.875rem;">#{{ $order->order_number }}</span>
</div>

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.75rem;">
    <div>
        <h1 class="section-heading">Detail Pesanan</h1>
        <p class="section-subheading">{{ $order->created_at->format('d F Y, H:i') }} WIB</p>
    </div>
    <div style="display:flex; gap:0.75rem; align-items:center;">
        <span class="badge badge-{{ $order->status }}" style="font-size:0.85rem; padding:0.4rem 1rem;">{{ ucfirst($order->status) }}</span>
        @if($order->status === 'shipped')
        <a href="{{ route('shipping.track', $order->id) }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
            Lacak Pengiriman
        </a>
        @endif
    </div>
</div>

<div class="order-detail-layout">
    <div>
        {{-- Items --}}
        <div class="card section-card">
            <div class="section-card-title">Item Pesanan</div>
            <table class="cf-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>
                            <div style="display:flex; gap:0.85rem; align-items:center;">
                                <div style="width:44px;height:38px;border-radius:4px;background:var(--cream);flex-shrink:0;overflow:hidden;">
                                    @if($item->product?->thumbnail)
                                        <img src="{{ asset('storage/' . $item->product->thumbnail) }}" style="width:100%;height:100%;object-fit:cover;">
                                    @endif
                                </div>
                                <div>
                                    <div style="font-weight:500;font-size:0.875rem;">{{ $item->product_name }}</div>
                                    @if($item->product)
                                    <a href="{{ route('products.show', $item->product->slug) }}" style="font-size:0.78rem;color:var(--muted);">Lihat produk</a>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td style="text-align:right;font-weight:600;">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right;color:var(--muted);font-size:0.85rem;">Subtotal Produk</td>
                        <td style="text-align:right;">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;color:var(--muted);font-size:0.85rem;">Ongkos Kirim ({{ $order->shipping_service }})</td>
                        <td style="text-align:right;">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right;font-weight:700;">Total</td>
                        <td style="text-align:right;font-weight:700;font-size:1.05rem;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        @if($order->notes)
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="section-card-title">Catatan Pesanan</div>
            <div style="padding:1.25rem 1.5rem;font-size:0.875rem;color:var(--muted);">{{ $order->notes }}</div>
        </div>
        @endif
    </div>

    {{-- Right sidebar --}}
    <div>
        <div class="card section-card">
            <div class="section-card-title">Informasi Pengiriman</div>
            <div class="info-row"><span class="label">Penerima</span><span class="value">{{ $order->recipient_name }}</span></div>
            <div class="info-row"><span class="label">Telepon</span><span class="value">{{ $order->phone }}</span></div>
            <div class="info-row"><span class="label">Alamat</span><span class="value">{{ $order->address }}</span></div>
            <div class="info-row"><span class="label">Kota</span><span class="value">{{ $order->city }}</span></div>
            <div class="info-row"><span class="label">Provinsi</span><span class="value">{{ $order->province }}</span></div>
            <div class="info-row"><span class="label">Kode Pos</span><span class="value">{{ $order->postal_code }}</span></div>
        </div>

        <div class="card section-card">
            <div class="section-card-title">Info Pengiriman</div>
            <div class="info-row"><span class="label">Kurir</span><span class="value">{{ $order->shipping_service ?? '—' }}</span></div>
            @if($order->tracking_number)
            <div class="info-row">
                <span class="label">No. Resi</span>
                <span class="value" style="font-family:monospace;">{{ $order->tracking_number }}</span>
            </div>
            @endif
            @if($order->estimated_delivery)
            <div class="info-row"><span class="label">Est. Tiba</span><span class="value">{{ $order->estimated_delivery }}</span></div>
            @endif
        </div>

        @if($order->status === 'pending')
        <form method="POST" action="{{ route('orders.cancel', $order->id) }}">
            @csrf @method('PATCH')
            <button type="submit" class="btn btn-outline" style="width:100%; justify-content:center; color:var(--danger); border-color:var(--danger);"
                onclick="return confirm('Yakin ingin membatalkan pesanan ini?')">
                Batalkan Pesanan
            </button>
        </form>
        @endif
    </div>
</div>
@endsection
