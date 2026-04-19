<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1C1C1A; }

    .header {
        border-bottom: 2px solid #1C1C1A;
        padding-bottom: 12px;
        margin-bottom: 18px;
        display: flex;
        justify-content: space-between;
    }
    .brand { font-size: 20px; font-weight: bold; }
    .brand span { color: #8B6914; }
    .report-meta { text-align: right; color: #6B6B5F; font-size: 10px; }
    .report-title { font-size: 14px; font-weight: bold; margin-bottom: 4px; }

    .kpi-row { display: flex; gap: 12px; margin-bottom: 18px; }
    .kpi-box {
        flex: 1;
        background: #F5F0E8;
        border-radius: 5px;
        padding: 10px 12px;
    }
    .kpi-label { font-size: 8px; text-transform: uppercase; letter-spacing: 0.06em; color: #6B6B5F; margin-bottom: 3px; }
    .kpi-value { font-size: 13px; font-weight: bold; }

    table { width: 100%; border-collapse: collapse; font-size: 10px; }
    th {
        background: #1C1C1A;
        color: #fff;
        padding: 6px 8px;
        text-align: left;
        font-weight: 600;
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    td { padding: 7px 8px; border-bottom: 1px solid #E2DDD4; }
    tr:nth-child(even) td { background: #FAFAF7; }

    .badge {
        display: inline-block;
        padding: 2px 7px;
        border-radius: 10px;
        font-size: 8px;
        font-weight: 600;
    }
    .badge-pending   { background: #FFF3CD; color: #856404; }
    .badge-paid      { background: #D4EDDA; color: #155724; }
    .badge-shipped   { background: #CCE5FF; color: #004085; }
    .badge-delivered { background: #D4EDDA; color: #155724; }
    .badge-cancelled { background: #F8D7DA; color: #721C24; }

    .footer { margin-top: 18px; text-align: center; color: #6B6B5F; font-size: 9px; }
</style>
</head>
<body>

<div class="header">
    <div>
        <div class="brand">Casa<span>Forma</span></div>
        <div style="font-size:10px; color:#6B6B5F; margin-top:3px;">Laporan Resmi CasaForma Furniture Marketplace</div>
    </div>
    <div class="report-meta">
        <div class="report-title">Laporan Penjualan</div>
        <div>Periode: {{ $startDate->format('d M Y') }} — {{ $endDate->format('d M Y') }}</div>
        <div>Digenerate: {{ now()->format('d M Y, H:i') }} WIB</div>
    </div>
</div>

@php
    $totalRevenue = $orders->whereNotIn('status', ['cancelled'])->sum('total_amount');
    $totalOrders  = $orders->count();
    $avgOrder     = $totalOrders > 0 ? round($totalRevenue / $totalOrders) : 0;
    $totalItems   = $orders->sum(fn($o) => $o->items->sum('quantity'));
@endphp

<div class="kpi-row">
    <div class="kpi-box">
        <div class="kpi-label">Total Pendapatan</div>
        <div class="kpi-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Total Pesanan</div>
        <div class="kpi-value">{{ number_format($totalOrders) }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Rata-rata Order</div>
        <div class="kpi-value">Rp {{ number_format($avgOrder, 0, ',', '.') }}</div>
    </div>
    <div class="kpi-box">
        <div class="kpi-label">Item Terjual</div>
        <div class="kpi-value">{{ number_format($totalItems) }}</div>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th>No. Pesanan</th>
            <th>Tanggal</th>
            <th>Pelanggan</th>
            <th>Status</th>
            <th>Kurir</th>
            <th>Ongkir</th>
            <th style="text-align:right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>#{{ $order->order_number }}</td>
            <td>{{ $order->created_at->format('d/m/Y') }}</td>
            <td>{{ $order->user->name ?? 'Guest' }}</td>
            <td><span class="badge badge-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
            <td>{{ strtoupper($order->courier ?? '—') }}</td>
            <td>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</td>
            <td style="text-align:right; font-weight:bold;">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    © {{ date('Y') }} CasaForma Furniture Marketplace — Dokumen ini digenerate secara otomatis oleh sistem.
</div>
</body>
</html>
