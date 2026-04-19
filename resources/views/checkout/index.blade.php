@extends('layouts.app')

@section('title', 'Checkout')

@push('styles')
<style>
    .checkout-layout {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 2rem;
        align-items: start;
    }

    /* Steps */
    .checkout-steps {
        display: flex;
        gap: 0;
        margin-bottom: 2rem;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
    }
    .step {
        flex: 1;
        padding: 1rem;
        text-align: center;
        font-size: 0.8rem;
        color: var(--muted);
        border-right: 1px solid var(--border);
        position: relative;
    }
    .step:last-child { border-right: none; }
    .step.active { color: var(--charcoal); background: var(--cream); }
    .step-num {
        display: block;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: var(--border);
        color: var(--muted);
        font-weight: 600;
        font-size: 0.75rem;
        margin: 0 auto 0.35rem;
        line-height: 24px;
    }
    .step.active .step-num {
        background: var(--charcoal);
        color: #fff;
    }
    .step.done .step-num {
        background: var(--success);
        color: #fff;
    }

    /* Form */
    .form-section {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 1.25rem;
    }
    .form-section-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--border);
        background: var(--cream);
        font-weight: 600;
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .form-section-body { padding: 1.5rem; }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }
    .form-grid.three { grid-template-columns: 1fr 1fr 1fr; }
    .form-group { margin-bottom: 1rem; }
    .form-group.full { grid-column: 1/-1; }
    label {
        display: block;
        font-size: 0.82rem;
        font-weight: 500;
        color: var(--muted);
        margin-bottom: 0.35rem;
    }
    input[type=text], input[type=tel], input[type=email], textarea, select.form-control {
        width: 100%;
        padding: 0.7rem 1rem;
        border: 1.5px solid var(--border);
        border-radius: 5px;
        font-family: var(--font-body);
        font-size: 0.9rem;
        color: var(--charcoal);
        background: #fff;
        outline: none;
        transition: border-color 0.2s;
    }
    input[type=text]:focus, input[type=tel]:focus, input[type=email]:focus,
    textarea:focus, select.form-control:focus { border-color: var(--accent); }
    textarea { resize: vertical; min-height: 80px; }
    .form-error { font-size: 0.78rem; color: var(--danger); margin-top: 0.25rem; }

    /* Shipping options */
    .shipping-option {
        border: 1.5px solid var(--border);
        border-radius: 6px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: pointer;
        transition: border-color 0.2s, background 0.2s;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .shipping-option:has(input:checked) {
        border-color: var(--charcoal);
        background: var(--cream);
    }
    .shipping-option input[type=radio] { accent-color: var(--charcoal); }
    .shipping-info { flex: 1; }
    .shipping-name { font-weight: 500; font-size: 0.9rem; }
    .shipping-eta { font-size: 0.8rem; color: var(--muted); }
    .shipping-cost { font-weight: 600; font-size: 0.95rem; }

    /* Order Summary */
    .order-summary { position: sticky; top: 80px; }
    .order-summary .card-body { padding: 1.75rem; }
    .summary-title {
        font-family: var(--font-display);
        font-size: 1.1rem;
        margin-bottom: 1.25rem;
        color: var(--charcoal);
    }
    .order-item {
        display: flex;
        gap: 0.85rem;
        margin-bottom: 1rem;
        align-items: center;
    }
    .order-item-img {
        width: 52px;
        height: 44px;
        border-radius: 4px;
        background: var(--cream);
        object-fit: cover;
        flex-shrink: 0;
    }
    .order-item-img img { width: 100%; height: 100%; object-fit: cover; border-radius: 4px; }
    .order-item-name { font-size: 0.85rem; font-weight: 500; color: var(--charcoal); }
    .order-item-qty { font-size: 0.78rem; color: var(--muted); }
    .order-item-price { font-size: 0.875rem; font-weight: 600; color: var(--charcoal); margin-left: auto; flex-shrink: 0; }
    .summary-divider { height: 1px; background: var(--border); margin: 1.25rem 0; }
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.875rem;
        color: var(--muted);
    }
    .summary-row span:last-child { color: var(--charcoal); }
    .summary-row.total {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--charcoal);
        padding-top: 1rem;
        border-top: 1px solid var(--border);
        margin-top: 0.5rem;
    }
    .summary-row.total span:last-child { color: var(--charcoal); }

    /* Rajaongkir notice */
    .rajaongkir-notice {
        background: var(--cream);
        border: 1px solid var(--border);
        border-radius: 6px;
        padding: 0.85rem 1rem;
        font-size: 0.8rem;
        color: var(--muted);
        margin-top: 0.75rem;
        display: flex;
        gap: 0.5rem;
    }
</style>
@endpush

@section('content')
{{-- Steps --}}
<div class="checkout-steps">
    <div class="step active">
        <span class="step-num">1</span>
        Alamat & Pengiriman
    </div>
    <div class="step">
        <span class="step-num">2</span>
        Ringkasan Order
    </div>
    <div class="step">
        <span class="step-num">3</span>
        Konfirmasi
    </div>
</div>

<form method="POST" action="{{ route('checkout.process') }}" id="checkoutForm">
@csrf
<div class="checkout-layout">

    {{-- LEFT: Form --}}
    <div>
        {{-- Shipping Address --}}
        <div class="form-section">
            <div class="form-section-header">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                Alamat Pengiriman
            </div>
            <div class="form-section-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nama Penerima *</label>
                        <input type="text" name="recipient_name" value="{{ old('recipient_name', auth()->user()->name ?? '') }}" required>
                        @error('recipient_name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Nomor Telepon *</label>
                        <input type="tel" name="phone" value="{{ old('phone') }}" placeholder="08xx-xxxx-xxxx" required>
                        @error('phone') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group full">
                        <label>Alamat Lengkap *</label>
                        <textarea name="address" required>{{ old('address') }}</textarea>
                        @error('address') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Provinsi *</label>
                        <select name="province_id" class="form-control" id="provinceSelect" required onchange="loadCities(this.value)">
                            <option value="">Pilih Provinsi</option>
                            @foreach($provinces as $prov)
                                <option value="{{ $prov['province_id'] }}" {{ old('province_id') == $prov['province_id'] ? 'selected' : '' }}>{{ $prov['province'] }}</option>
                            @endforeach
                        </select>
                        @error('province_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kota / Kabupaten *</label>
                        <select name="city_id" class="form-control" id="citySelect" required>
                            <option value="">Pilih Kota</option>
                        </select>
                        @error('city_id') <div class="form-error">{{ $message }}</div> @enderror
                    </div>
                    <div class="form-group">
                        <label>Kode Pos *</label>
                        <input type="text" name="postal_code" value="{{ old('postal_code') }}" placeholder="00000" required>
                    </div>
                    <div class="form-group full">
                        <label>Catatan untuk Penjual (opsional)</label>
                        <textarea name="notes" placeholder="Instruksi khusus pengiriman, dll.">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- Shipping Method (from RajaOngkir) --}}
        <div class="form-section">
            <div class="form-section-header">
                <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="1" y="3" width="15" height="13"/><polygon points="16 8 20 8 23 11 23 16 16 16 16 8"/><circle cx="5.5" cy="18.5" r="2.5"/><circle cx="18.5" cy="18.5" r="2.5"/></svg>
                Metode Pengiriman
            </div>
            <div class="form-section-body">
                <div id="shipping-options-wrap">
                    @if(isset($shippingOptions) && count($shippingOptions) > 0)
                        @foreach($shippingOptions as $option)
                        <label class="shipping-option">
                            <input type="radio" name="shipping_service" value="{{ $option['code'] }}_{{ $option['service'] }}"
                                   data-cost="{{ $option['cost'] }}"
                                   onchange="updateShippingCost({{ $option['cost'] }})">
                            <div class="shipping-info">
                                <div class="shipping-name">{{ strtoupper($option['code']) }} — {{ $option['service'] }}</div>
                                <div class="shipping-eta">Estimasi {{ $option['etd'] }} hari kerja</div>
                            </div>
                            <div class="shipping-cost">Rp {{ number_format($option['cost'], 0, ',', '.') }}</div>
                        </label>
                        @endforeach
                    @else
                        <div class="rajaongkir-notice">
                            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                            Pilih provinsi dan kota terlebih dahulu untuk melihat opsi pengiriman (via RajaOngkir).
                        </div>
                        <input type="hidden" name="shipping_cost" id="shippingCostInput" value="0">
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Order Summary --}}
    <div class="order-summary">
        <div class="card">
            <div class="card-body">
                <div class="summary-title">Ringkasan Order</div>

                @foreach($cartItems as $id => $item)
                <div class="order-item">
                    <div class="order-item-img">
                        @if(isset($item['thumbnail']))
                            <img src="{{ asset('storage/' . $item['thumbnail']) }}" alt="{{ $item['name'] }}">
                        @else
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="opacity:0.3; margin:auto; display:block"><rect x="3" y="3" width="18" height="18" rx="2"/></svg>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div class="order-item-name">{{ $item['name'] }}</div>
                        <div class="order-item-qty">× {{ $item['quantity'] }}</div>
                    </div>
                    <div class="order-item-price">Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}</div>
                </div>
                @endforeach

                <div class="summary-divider"></div>

                <div class="summary-row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="summary-row">
                    <span>Ongkos Kirim</span>
                    <span id="shippingDisplay">Rp 0</span>
                </div>
                <div class="summary-row total">
                    <span>Total</span>
                    <span id="grandTotalDisplay">Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>

                <button type="submit" class="btn btn-accent btn-lg" style="width:100%; justify-content:center;">
                    Konfirmasi Pesanan
                    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        </div>
    </div>
</div>
</form>
@endsection

@push('scripts')
<script>
const baseSubtotal = {{ $subtotal }};

function formatRupiah(num) {
    return 'Rp ' + num.toLocaleString('id-ID');
}

function updateShippingCost(cost) {
    document.getElementById('shippingDisplay').textContent = formatRupiah(cost);
    document.getElementById('grandTotalDisplay').textContent = formatRupiah(baseSubtotal + cost);
    const hidden = document.getElementById('shippingCostInput');
    if (hidden) hidden.value = cost;
}

function loadCities(provinceId) {
    if (!provinceId) return;
    fetch(`/api/rajaongkir/cities/${provinceId}`)
        .then(r => r.json())
        .then(data => {
            const sel = document.getElementById('citySelect');
            sel.innerHTML = '<option value="">Pilih Kota</option>';
            data.forEach(c => {
                sel.innerHTML += `<option value="${c.city_id}">${c.type} ${c.city_name}</option>`;
            });
            // Reload shipping options
            document.getElementById('shipping-options-wrap').innerHTML =
                '<div class="rajaongkir-notice">Pilih kota untuk melihat opsi pengiriman.</div>';
        });
}

// When city changes, fetch shipping
document.getElementById('citySelect')?.addEventListener('change', function() {
    const cityId = this.value;
    if (!cityId) return;
    fetch(`/api/rajaongkir/shipping-options?city_id=${cityId}&weight={{ $totalWeight ?? 1000 }}`)
        .then(r => r.json())
        .then(data => {
            const wrap = document.getElementById('shipping-options-wrap');
            if (!data.length) { wrap.innerHTML = '<p style="color:var(--muted);font-size:0.875rem">Tidak ada opsi pengiriman tersedia.</p>'; return; }
            wrap.innerHTML = data.map(o => `
                <label class="shipping-option">
                    <input type="radio" name="shipping_service" value="${o.code}_${o.service}" data-cost="${o.cost}" onchange="updateShippingCost(${o.cost})">
                    <div class="shipping-info">
                        <div class="shipping-name">${o.code.toUpperCase()} — ${o.service}</div>
                        <div class="shipping-eta">Estimasi ${o.etd} hari kerja</div>
                    </div>
                    <div class="shipping-cost">${formatRupiah(o.cost)}</div>
                </label>
            `).join('');
        });
});
</script>
@endpush
