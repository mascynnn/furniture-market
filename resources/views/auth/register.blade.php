@extends('layouts.app')
@section('title', 'Daftar — CasaForma')

@push('styles')
<style>
.auth-page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 68px);
}
.auth-visual {
    position: relative;
    overflow: hidden;
    background: #0a0908;
}
.auth-visual img {
    width: 100%; height: 100%;
    object-fit: cover;
    opacity: 0.55;
}
.auth-visual__overlay {
    position: absolute; inset: 0;
    display: flex; flex-direction: column; justify-content: flex-end;
    padding: 52px;
    background: linear-gradient(to top, rgba(13,12,10,0.85) 0%, transparent 60%);
}
.auth-visual__quote {
    font-family: var(--font-display);
    font-size: 36px; font-weight: 300; font-style: italic;
    color: var(--cream);
    line-height: 1.3;
    margin-bottom: 16px;
}
.auth-visual__sub {
    font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase;
    color: var(--gold);
}
.auth-form-panel {
    display: flex; align-items: center; justify-content: center;
    padding: 64px 72px;
    background: var(--bg);
}
.auth-form-wrap { width: 100%; max-width: 420px; }
.auth-form-wrap .section-label { margin-bottom: 8px; }
.auth-form-wrap h1 {
    font-family: var(--font-display);
    font-size: 38px; font-weight: 400;
    color: var(--cream);
    margin-bottom: 36px;
    line-height: 1.2;
}
.auth-footer {
    margin-top: 28px;
    text-align: center;
    font-size: 13px;
    color: var(--text-muted);
}
.auth-footer a { color: var(--gold); }

.role-selector {
    display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
    margin-bottom: 24px;
}
.role-option { display: none; }
.role-label {
    display: flex; flex-direction: column; align-items: center;
    gap: 8px; padding: 16px;
    border: 1px solid var(--border);
    cursor: pointer;
    transition: var(--transition);
    font-size: 12px; letter-spacing: 0.1em; text-transform: uppercase;
    color: var(--text-muted);
}
.role-label svg { width: 22px; height: 22px; }
.role-option:checked + .role-label {
    border-color: var(--gold);
    color: var(--gold);
    background: rgba(200,169,110,0.06);
}
</style>
@endpush

@section('content')
<div class="auth-page">
    {{-- Visual Panel --}}
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=900&auto=format&fit=crop" alt="Interior CasaForma">
        <div class="auth-visual__overlay">
            <p class="auth-visual__quote">"Rumah adalah karya seni yang Anda tinggali."</p>
            <p class="auth-visual__sub">CasaForma — Furniture Pilihan</p>
        </div>
    </div>

    {{-- Form Panel --}}
    <div class="auth-form-panel">
        <div class="auth-form-wrap">
            <p class="section-label">Bergabung dengan CasaForma</p>
            <h1>Buat Akun<br><em style="color:var(--gold)">Gratis</em></h1>

            <form action="{{ route('register') }}" method="POST">
                @csrf

                {{-- Role --}}
                <div class="form-group">
                    <label class="form-label">Saya ingin</label>
                    <div class="role-selector">
                        <div>
                            <input type="radio" name="role" id="role_buyer" value="buyer" class="role-option"
                                {{ old('role', 'buyer') == 'buyer' ? 'checked' : '' }}>
                            <label for="role_buyer" class="role-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                Berbelanja
                            </label>
                        </div>
                        <div>
                            <input type="radio" name="role" id="role_seller" value="seller" class="role-option"
                                {{ old('role') == 'seller' ? 'checked' : '' }}>
                            <label for="role_seller" class="role-label">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                Berjualan
                            </label>
                        </div>
                    </div>
                    @error('role')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}"
                        class="form-control" placeholder="Nama Anda" required autofocus>
                    @error('name')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control" placeholder="nama@email.com" required>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="phone" class="form-label">No. Telepon <span style="color:var(--text-muted)">(opsional)</span></label>
                    <input id="phone" type="text" name="phone" value="{{ old('phone') }}"
                        class="form-control" placeholder="08xx-xxxx-xxxx">
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password"
                        class="form-control" placeholder="Minimal 8 karakter" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control" placeholder="Ulangi password" required>
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    Buat Akun Sekarang
                </button>
            </form>

            <p class="auth-footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </p>
        </div>
    </div>
</div>
@endsection
