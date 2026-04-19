@extends('layouts.app')

@section('title', 'Konfirmasi Pesanan')

@push('styles')
<style>
    .confirm-wrap { max-width: 680px; margin: 0 auto; }
    .confirm-icon {
        width: 72px; height: 72px;
        border-radius: 50%;
        background: #d4edda;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
    }
    .confirm-header { text-align: center; margin-bottom: 2rem; }
    .confirm-header h1 {
        font-family: var(--font-display);
        font-size: 2rem;
        color: var(--charcoal);
        margin-bottom: 0.5rem;
    }
    .order-id {
        font-size: 0.875rem;
        color: var(--muted);
    }
    .order-id strong { color: var(--charcoal); }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem; }
    .info-block {
        background: var(--cream);
        border-radius: 7px;
        padding: 1.25rem;
    }
    .info-label { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); margin-bottom: 0.5rem; }
    .info-value { font-size: 0.9rem; color: var(--charcoal); font-weight: 500; line-height: 1.5; }

    .actions-row { display: flex; gap: 1rem; justify-content: center; margin-top: 2rem; }
</style>
@endpush

@section('content')
<div class="confirm-wrap">
    <div class="confirm-header">
        <div class="confirm-icon">
            <svg width="36" height="36" fill="none" stroke="#155724" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        </div>
        <h1>Pesanan Dikonfirmasi!</h1>
        <p class="order-id">Terima kasih, <strong>{{ $order->user->name }}</strong>. Pesanan Anda sedang diproses.</p>
        <p class="order-id" style="margin-top:0.35rem">ID Pesanan: <strong>#{{ $order->order_number }}</strong></p>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="info-grid">
                <div class="info-block">
                    <div class="info-label">Dikirim ke</div>
                    <div class="info-value">
                        {{ $order->recipient_name }}<br>
                        {{ $order->address }}<br>
                        {{ $order->city }}, {{ $order->province }} {{ $order->postal_code }}<br>
                        {{ $order->phone }}
                    </div>
                </div>
                <div class="info-block">
                    <div class="info-label">Metode Pengiriman</div>
                    <div class="info-value">{{ $order->shipping_service }}</div>
                    <div class="info-label" style="margin-top:1rem">Estimasi Tiba</div>
                    <div class="info-value">{{ $order->estimated_delivery }}</div>
                </div>
            </div>

            <table class="cf-table">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th style="text-align:right">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                    <tr>
                        <td>{{ $item->product->name ?? $item->product_name }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td style="text-align:right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-align:right; color:var(--muted); font-size:0.85rem">Ongkir</td>
                        <td style="text-align:right">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td colspan="3" style="text-align:right; font-weight:600">Total</td>
                        <td style="text-align:right; font-weight:700; font-size:1.05rem">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <div class="actions-row">
        <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary">Lihat Detail Pesanan</a>
        <a href="{{ route('search.index') }}" class="btn btn-outline">Lanjut Belanja</a>
    </div>
</div>
@endsection
