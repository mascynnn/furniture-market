@extends('layouts.app')

@section('title', 'Laporan Penjualan')

@push('styles')
<style>
    .report-toolbar {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.75rem;
        display: flex;
        gap: 1rem;
        align-items: center;
        flex-wrap: wrap;
    }
    .report-toolbar label { font-size: 0.82rem; color: var(--muted); font-weight: 500; margin-bottom: 0; }
    .toolbar-group { display: flex; flex-direction: column; gap: 0.3rem; }
    .report-toolbar input[type=date] {
        padding: 0.5rem 0.75rem;
        border: 1.5px solid var(--border);
        border-radius: 5px;
        font-family: var(--font-body);
        font-size: 0.875rem;
        outline: none;
        color: var(--charcoal);
    }
    .report-toolbar input[type=date]:focus { border-color: var(--accent); }
    .period-chips { display: flex; gap: 0.35rem; }
    .period-chip {
        padding: 0.35rem 0.85rem;
        border-radius: 100px;
        font-size: 0.78rem;
        font-weight: 500;
        border: 1.5px solid var(--border);
        background: #fff;
        color: var(--muted);
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .period-chip:hover, .period-chip.active {
        background: var(--charcoal);
        color: #fff;
        border-color: var(--charcoal);
    }
    .toolbar-actions { margin-left: auto; display: flex; gap: 0.5rem; }

    /* KPI Cards */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1rem;
        margin-bottom: 1.75rem;
    }
    .kpi-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 1.5rem;
    }
    .kpi-label { font-size: 0.78rem; text-transform: uppercase; letter-spacing: 0.07em; color: var(--muted); margin-bottom: 0.75rem; }
    .kpi-value { font-family: var(--font-display); font-size: 1.75rem; font-weight: 600; color: var(--charcoal); line-height: 1; }
    .kpi-change {
        margin-top: 0.5rem;
        font-size: 0.8rem;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }
    .kpi-change.up { color: var(--success); }
    .kpi-change.down { color: var(--danger); }
    .kpi-sub { font-size: 0.78rem; color: var(--muted); margin-top: 0.35rem; }

    /* Table */
    .table-wrap {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.5rem;
    }
    .table-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .table-title { font-weight: 600; font-size: 0.95rem; }

    /* Empty state */
    .empty-report {
        text-align: center;
        padding: 3rem;
        color: var(--muted);
    }
</style>
@endpush

@section('content')
<h1 class="section-heading">Laporan Penjualan</h1>
<p class="section-subheading">Analisis performa penjualan CasaForma dalam periode waktu tertentu</p>

{{-- Toolbar --}}
<form method="GET" action="{{ route('reports.index') }}" id="reportForm">
<div class="report-toolbar">
    <div class="toolbar-group">
        <label>Periode Cepat</label>
        <div class="period-chips">
            <a href="{{ route('reports.index', ['period' => '7d']) }}" class="period-chip {{ request('period') == '7d' ? 'active' : '' }}">7 Hari</a>
            <a href="{{ route('reports.index', ['period' => '30d']) }}" class="period-chip {{ request('period') == '30d' ? 'active' : '' }}">30 Hari</a>
            <a href="{{ route('reports.index', ['period' => 'this_month']) }}" class="period-chip {{ request('period') == 'this_month' ? 'active' : '' }}">Bulan Ini</a>
            <a href="{{ route('reports.index', ['period' => 'last_month']) }}" class="period-chip {{ request('period') == 'last_month' ? 'active' : '' }}">Bulan Lalu</a>
            <a href="{{ route('reports.index', ['period' => 'this_year']) }}" class="period-chip {{ request('period') == 'this_year' ? 'active' : '' }}">Tahun Ini</a>
        </div>
    </div>

    <div class="toolbar-group">
        <label>Dari Tanggal</label>
        <input type="date" name="start_date" value="{{ request('start_date', $startDate ?? '') }}" onchange="document.getElementById('reportForm').submit()">
    </div>
    <div class="toolbar-group">
        <label>Sampai Tanggal</label>
        <input type="date" name="end_date" value="{{ request('end_date', $endDate ?? '') }}" onchange="document.getElementById('reportForm').submit()">
    </div>

    <div class="toolbar-actions">
        <a href="{{ route('reports.export', array_merge(request()->all(), ['format' => 'excel'])) }}" class="btn btn-outline btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
            Export Excel
        </a>
        <a href="{{ route('reports.export', array_merge(request()->all(), ['format' => 'pdf'])) }}" class="btn btn-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
            Export PDF
        </a>
    </div>
</div>
</form>

{{-- KPI Cards --}}
<div class="kpi-grid">
    <div class="kpi-card">
        <div class="kpi-label">Total Pendapatan</div>
        <div class="kpi-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        @if(isset($revenueChange))
        <div class="kpi-change {{ $revenueChange >= 0 ? 'up' : 'down' }}">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                @if($revenueChange >= 0)<polyline points="18 15 12 9 6 15"/>@else<polyline points="6 9 12 15 18 9"/>@endif
            </svg>
            {{ abs($revenueChange) }}% vs periode sebelumnya
        </div>
        @endif
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Total Pesanan</div>
        <div class="kpi-value">{{ number_format($totalOrders) }}</div>
        <div class="kpi-sub">{{ $completedOrders ?? 0 }} selesai · {{ $cancelledOrders ?? 0 }} dibatalkan</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Rata-rata Nilai Order</div>
        <div class="kpi-value">Rp {{ number_format($avgOrderValue, 0, ',', '.') }}</div>
        <div class="kpi-sub">Per transaksi</div>
    </div>
    <div class="kpi-card">
        <div class="kpi-label">Total Item Terjual</div>
        <div class="kpi-value">{{ number_format($totalItemsSold) }}</div>
        <div class="kpi-sub">{{ $uniqueProductsSold ?? 0 }} produk berbeda</div>
    </div>
</div>

{{-- Detailed Table --}}
<div class="table-wrap">
    <div class="table-header">
        <span class="table-title">Rincian Transaksi</span>
        <span style="font-size:0.82rem; color:var(--muted)">{{ $orders->total() }} transaksi ditemukan</span>
    </div>
    <div style="overflow-x: auto;">
        <table class="cf-table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Item</th>
                    <th>Ongkir</th>
                    <th>Total</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>
                        <a href="{{ route('orders.show', $order->id) }}" style="color:var(--charcoal); font-weight:500; text-decoration:none;">
                            #{{ $order->order_number }}
                        </a>
                    </td>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td>{{ $order->user->name ?? '—' }}</td>
                    <td>{{ $order->items->count() }} item</td>
                    <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
                    <td style="font-weight:600;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                </tr>
                @empty
                <tr><td colspan="7" class="empty-report">Tidak ada data untuk periode yang dipilih</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Top Products --}}
@if(isset($topProducts) && $topProducts->count() > 0)
<div class="table-wrap">
    <div class="table-header">
        <span class="table-title">Produk Terlaris</span>
    </div>
    <table class="cf-table">
        <thead>
            <tr>
                <th>Peringkat</th>
                <th>Produk</th>
                <th>Kategori</th>
                <th>Terjual</th>
                <th style="text-align:right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $i => $product)
            <tr>
                <td style="color:var(--muted); font-weight:500;">#{{ $i + 1 }}</td>
                <td>
                    <a href="{{ route('products.show', $product->slug) }}" style="color:var(--charcoal); font-weight:500; text-decoration:none;">
                        {{ $product->name }}
                    </a>
                </td>
                <td>{{ $product->category->name ?? '—' }}</td>
                <td>{{ $product->total_sold }} unit</td>
                <td style="text-align:right; font-weight:600;">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

{{-- Pagination --}}
@if($orders->hasPages())
<div style="margin-top:1.5rem; display:flex; justify-content:center;">
    {{ $orders->withQueryString()->links('vendor.pagination.casaforma') }}
</div>
@endif
@endsection
