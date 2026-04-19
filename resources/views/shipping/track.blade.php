@extends('layouts.app')

@section('title', 'Tracking Pengiriman #' . $order->order_number)

@push('styles')
<style>
    .tracking-layout {
        display: grid;
        grid-template-columns: 1fr 360px;
        gap: 2rem;
        align-items: start;
    }

    /* Status banner */
    .status-banner {
        border-radius: 10px;
        padding: 1.75rem;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }
    .status-banner.shipped  { background: #cce5ff; }
    .status-banner.delivered{ background: #d4edda; }
    .status-banner.pending  { background: #fff3cd; }
    .status-icon {
        width: 52px; height: 52px; border-radius: 50%;
        background: rgba(255,255,255,0.5);
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0;
    }
    .status-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.07em; opacity: 0.7; }
    .status-value { font-family: var(--font-display); font-size: 1.3rem; font-weight: 600; }
    .resi-code {
        margin-left: auto;
        text-align: right;
    }
    .resi-code .label { font-size: 0.78rem; opacity: 0.7; }
    .resi-code .code { font-size: 1rem; font-weight: 700; font-family: monospace; letter-spacing: 0.05em; }

    /* Timeline */
    .timeline {
        position: relative;
        padding-left: 2rem;
    }
    .timeline::before {
        content: '';
        position: absolute;
        left: 0.45rem;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 2px;
        background: var(--border);
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .timeline-item:last-child { margin-bottom: 0; }
    .timeline-dot {
        position: absolute;
        left: -2rem;
        top: 0.25rem;
        width: 14px;
        height: 14px;
        border-radius: 50%;
        background: var(--border);
        border: 2px solid #fff;
        box-shadow: 0 0 0 2px var(--border);
    }
    .timeline-dot.active {
        background: var(--charcoal);
        box-shadow: 0 0 0 3px rgba(28,28,26,0.15);
    }
    .timeline-dot.done {
        background: var(--success);
        box-shadow: 0 0 0 2px rgba(39,174,96,0.2);
    }
    .timeline-time { font-size: 0.78rem; color: var(--muted); margin-bottom: 0.25rem; }
    .timeline-status { font-weight: 600; font-size: 0.9rem; color: var(--charcoal); margin-bottom: 0.2rem; }
    .timeline-desc { font-size: 0.85rem; color: var(--muted); }
    .timeline-location { font-size: 0.8rem; color: var(--muted); margin-top: 0.2rem; }

    /* Resi form */
    .resi-form {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
    }
    .resi-form input {
        flex: 1;
        padding: 0.7rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 5px;
        font-family: var(--font-body);
        font-size: 0.9rem;
        outline: none;
    }
    .resi-form input:focus { border-color: var(--accent); }

    /* Order info */
    .order-info-list { list-style: none; }
    .order-info-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.65rem 0;
        border-bottom: 1px solid var(--border);
        font-size: 0.875rem;
    }
    .order-info-list li:last-child { border-bottom: none; }
    .order-info-list li span:first-child { color: var(--muted); }
    .order-info-list li span:last-child { font-weight: 500; text-align: right; max-width: 60%; }
</style>
@endpush

@section('content')
<div style="margin-bottom: 1.5rem; display: flex; align-items: center; gap: 0.75rem;">
    <a href="{{ route('orders.index') }}" style="color:var(--muted); text-decoration:none; font-size:0.875rem; display:flex; align-items:center; gap:0.3rem;">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
        Pesanan Saya
    </a>
    <span style="color:var(--border)">/</span>
    <span style="font-size:0.875rem; color:var(--charcoal)">#{{ $order->order_number }}</span>
</div>

{{-- Status Banner --}}
<div class="status-banner {{ $order->status }}">
    <div class="status-icon">
        @if($order->status == 'shipped')
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
        @elseif($order->status == 'delivered')
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="20 6 9 17 4 12"/></svg>
        @else
        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
        @endif
    </div>
    <div>
        <div class="status-label">Status Pengiriman</div>
        <div class="status-value">
            @switch($order->status)
                @case('pending') Menunggu Pembayaran @break
                @case('paid') Diproses Penjual @break
                @case('shipped') Dalam Pengiriman @break
                @case('delivered') Telah Diterima @break
                @default {{ ucfirst($order->status) }}
            @endswitch
        </div>
    </div>
    @if($order->tracking_number)
    <div class="resi-code">
        <div class="label">Nomor Resi</div>
        <div class="code">{{ $order->tracking_number }}</div>
        <div style="font-size:0.78rem; opacity:0.7; margin-top:0.25rem">{{ $order->courier ?? 'Kurir' }}</div>
    </div>
    @endif
</div>

<div class="tracking-layout">
    {{-- LEFT: Timeline --}}
    <div>
        <div class="card" style="margin-bottom:1.25rem;">
            <div class="card-body">
                <h3 style="font-family:var(--font-display); font-size:1.1rem; margin-bottom:1.5rem;">Riwayat Pengiriman</h3>

                @if($trackingHistory && count($trackingHistory) > 0)
                <div class="timeline">
                    @foreach($trackingHistory as $i => $event)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $i === 0 ? 'active' : 'done' }}"></div>
                        <div class="timeline-time">{{ $event['date'] }} · {{ $event['time'] }}</div>
                        <div class="timeline-status">{{ $event['status'] }}</div>
                        <div class="timeline-desc">{{ $event['description'] }}</div>
                        @if(isset($event['location']))
                        <div class="timeline-location">
                            <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="vertical-align:middle"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                            {{ $event['location'] }}
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                {{-- Progress Steps (before resi assigned) --}}
                <div class="timeline">
                    @php
                        $steps = [
                            ['status' => 'pending',   'label' => 'Pesanan Dibuat',     'desc' => 'Pesanan Anda telah berhasil dibuat'],
                            ['status' => 'paid',      'label' => 'Pembayaran Dikonfirmasi', 'desc' => 'Pembayaran telah dikonfirmasi oleh sistem'],
                            ['status' => 'shipped',   'label' => 'Dalam Pengiriman',   'desc' => 'Paket sedang dalam perjalanan ke Anda'],
                            ['status' => 'delivered', 'label' => 'Pesanan Diterima',   'desc' => 'Paket telah sampai di tujuan'],
                        ];
                        $statusOrder = ['pending', 'paid', 'shipped', 'delivered'];
                        $currentIdx = array_search($order->status, $statusOrder);
                    @endphp
                    @foreach($steps as $i => $step)
                    <div class="timeline-item">
                        <div class="timeline-dot {{ $i == $currentIdx ? 'active' : ($i < $currentIdx ? 'done' : '') }}"></div>
                        @if($i == 0)
                        <div class="timeline-time">{{ $order->created_at->format('d M Y, H:i') }}</div>
                        @elseif($i <= $currentIdx)
                        <div class="timeline-time">{{ $order->updated_at->format('d M Y, H:i') }}</div>
                        @endif
                        <div class="timeline-status" style="{{ $i > $currentIdx ? 'color:var(--muted); font-weight:400' : '' }}">{{ $step['label'] }}</div>
                        <div class="timeline-desc">{{ $step['desc'] }}</div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        {{-- Manual resi update (for seller/admin) --}}
        @if(auth()->user()?->role === 'seller' || auth()->user()?->role === 'admin')
        <div class="card">
            <div class="card-body">
                <h4 style="font-weight:600; margin-bottom:1rem; font-size:0.9rem;">Update Resi Pengiriman</h4>
                <form method="POST" action="{{ route('shipping.update-resi', $order->id) }}" class="resi-form">
                    @csrf @method('PATCH')
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Masukkan nomor resi...">
                    <select name="courier" class="filter-select" style="padding:0.7rem 0.85rem;">
                        <option value="jne" {{ $order->courier == 'jne' ? 'selected' : '' }}>JNE</option>
                        <option value="jnt" {{ $order->courier == 'jnt' ? 'selected' : '' }}>J&T</option>
                        <option value="pos" {{ $order->courier == 'pos' ? 'selected' : '' }}>Pos Indonesia</option>
                        <option value="tiki" {{ $order->courier == 'tiki' ? 'selected' : '' }}>TIKI</option>
                        <option value="anteraja" {{ $order->courier == 'anteraja' ? 'selected' : '' }}>Anteraja</option>
                    </select>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
        @endif
    </div>

    {{-- RIGHT: Order Info --}}
    <div>
        <div class="card">
            <div class="card-body">
                <h4 style="font-family:var(--font-display); font-size:1.05rem; margin-bottom:1.25rem;">Detail Pesanan</h4>
                <ul class="order-info-list">
                    <li><span>No. Pesanan</span><span>#{{ $order->order_number }}</span></li>
                    <li><span>Tanggal</span><span>{{ $order->created_at->format('d M Y') }}</span></li>
                    <li><span>Status</span><span><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></span></li>
                    @if($order->tracking_number)
                    <li><span>Resi</span><span style="font-family:monospace">{{ $order->tracking_number }}</span></li>
                    @endif
                    @if($order->estimated_delivery)
                    <li><span>Est. Tiba</span><span>{{ $order->estimated_delivery }}</span></li>
                    @endif
                </ul>
            </div>
        </div>

        <div class="card" style="margin-top:1rem;">
            <div class="card-body">
                <h4 style="font-family:var(--font-display); font-size:1.05rem; margin-bottom:1.25rem;">Alamat Pengiriman</h4>
                <ul class="order-info-list">
                    <li><span>Penerima</span><span>{{ $order->recipient_name }}</span></li>
                    <li><span>Telepon</span><span>{{ $order->phone }}</span></li>
                    <li><span>Alamat</span><span>{{ $order->address }}</span></li>
                    <li><span>Kota</span><span>{{ $order->city }}</span></li>
                    <li><span>Provinsi</span><span>{{ $order->province }}</span></li>
                </ul>
            </div>
        </div>

        <div class="card" style="margin-top:1rem;">
            <div class="card-body">
                <h4 style="font-family:var(--font-display); font-size:1.05rem; margin-bottom:1.25rem;">Ringkasan Pembayaran</h4>
                <ul class="order-info-list">
                    <li><span>Subtotal Produk</span><span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></li>
                    <li><span>Ongkos Kirim</span><span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></li>
                    <li style="font-weight:600;"><span>Total</span><span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></li>
                </ul>
            </div>
        </div>

        @if($order->status == 'delivered')
        <div style="margin-top:1rem;">
            <a href="{{ route('products.show', $order->items->first()->product->slug ?? '#') }}" class="btn btn-outline" style="width:100%; justify-content:center; margin-bottom:0.5rem;">Beri Ulasan Produk</a>
        </div>
        @endif
    </div>
</div>
@endsection
