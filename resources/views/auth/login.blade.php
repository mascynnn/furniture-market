@extends('layouts.app')
@section('title', 'Masuk — CasaForma')

@push('styles')
<style>
.auth-page {
    display: grid;
    grid-template-columns: 1fr 1fr;
    min-height: calc(100vh - 68px);
}
.auth-visual {
    position: relative; overflow: hidden; background: #0a0908;
}
.auth-visual img {
    width: 100%; height: 100%; object-fit: cover; opacity: 0.5;
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
    color: var(--cream); line-height: 1.3; margin-bottom: 16px;
}
.auth-visual__sub {
    font-size: 12px; letter-spacing: 0.14em; text-transform: uppercase; color: var(--gold);
}
.auth-form-panel {
    display: flex; align-items: center; justify-content: center;
    padding: 64px 72px; background: var(--bg);
}
.auth-form-wrap { width: 100%; max-width: 400px; }
.auth-form-wrap h1 {
    font-family: var(--font-display);
    font-size: 42px; font-weight: 400; color: var(--cream);
    margin-bottom: 40px; line-height: 1.2;
}
.auth-remember {
    display: flex; align-items: center; gap: 10px;
    margin-bottom: 24px;
    font-size: 13px; color: var(--text-muted);
    cursor: pointer;
}
.auth-remember input { accent-color: var(--gold); width: 14px; height: 14px; }
.auth-footer {
    margin-top: 28px; text-align: center;
    font-size: 13px; color: var(--text-muted);
}
.auth-footer a { color: var(--gold); }
</style>
@endpush

@section('content')
<div class="auth-page">
    <div class="auth-visual">
        <img src="https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=900&auto=format&fit=crop" alt="Interior Elegan">
        <div class="auth-visual__overlay">
            <p class="auth-visual__quote">"Setiap ruangan bercerita tentang yang menhuninya."</p>
            <p class="auth-visual__sub">CasaForma — Furniture Pilihan</p>
        </div>
    </div>

    <div class="auth-form-panel">
        <div class="auth-form-wrap">
            <p class="section-label">Selamat datang kembali</p>
            <h1>Masuk ke<br><em style="color:var(--gold)">CasaForma</em></h1>

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                        class="form-control" placeholder="nama@email.com" required autofocus>
                    @error('email')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password"
                        class="form-control" placeholder="Password Anda" required>
                    @error('password')<p class="form-error">{{ $message }}</p>@enderror
                </div>

                <label class="auth-remember">
                    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                    Ingat saya
                </label>

                <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center">
                    Masuk Sekarang
                </button>
            </form>

            <p class="auth-footer" style="margin-top:16px">
                Belum punya akun? <a href="{{ route('register') }}">Daftar gratis</a>
            </p>
        </div>
    </div>
</div>
@endsection
