<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CasaForma — Mabel Furniture Pilihan')</title>

    {{-- Google Fonts: Cormorant Garamond (display) + DM Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;1,300;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════
           CSS VARIABLES — CasaForma Design System
        ══════════════════════════════════════════ */
        :root {
            --bg:        #0d0c0a;
            --bg-card:   #141210;
            --bg-hover:  #1a1815;
            --border:    rgba(200,180,140,0.12);
            --gold:      #c8a96e;
            --gold-dim:  #a8895a;
            --cream:     #f0ebe0;
            --cream-dim: #b8b0a0;
            --white:     #faf9f6;
            --text:      #e8e2d8;
            --text-muted:#8a8278;
            --red:       #c0392b;

            --font-display: 'Cormorant Garamond', Georgia, serif;
            --font-body:    'DM Sans', sans-serif;

            --radius-sm: 2px;
            --radius:    4px;
            --radius-lg: 8px;

            --transition: 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ── Reset ─────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { scroll-behavior: smooth; }
        body {
            font-family: var(--font-body);
            background: var(--bg);
            color: var(--text);
            font-size: 15px;
            line-height: 1.65;
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        img { display: block; max-width: 100%; }
        button, input, select, textarea { font-family: inherit; }

        /* ── Scrollbar ──────────────────────────── */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: var(--bg); }
        ::-webkit-scrollbar-thumb { background: var(--gold-dim); border-radius: 3px; }

        /* ══════════════════════════════════════════
           NAVBAR
        ══════════════════════════════════════════ */
        .navbar {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 48px;
            height: 68px;
            background: rgba(13,12,10,0.92);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            transition: var(--transition);
        }
        .navbar__logo {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--cream);
        }
        .navbar__logo span { color: var(--gold); }
        .navbar__nav {
            display: flex; align-items: center; gap: 36px;
            list-style: none;
        }
        .navbar__nav a {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            transition: color var(--transition);
        }
        .navbar__nav a:hover,
        .navbar__nav a.active { color: var(--gold); }
        .navbar__actions {
            display: flex; align-items: center; gap: 20px;
        }
        .navbar__icon {
            position: relative;
            width: 38px; height: 38px;
            display: flex; align-items: center; justify-content: center;
            color: var(--cream-dim);
            transition: color var(--transition);
            cursor: pointer;
        }
        .navbar__icon:hover { color: var(--gold); }
        .navbar__icon svg { width: 20px; height: 20px; }
        .badge {
            position: absolute; top: 4px; right: 4px;
            width: 16px; height: 16px;
            background: var(--gold);
            color: var(--bg);
            font-size: 9px;
            font-weight: 600;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            pointer-events: none;
        }
        .btn-nav {
            padding: 8px 20px;
            border: 1px solid var(--gold);
            color: var(--gold);
            font-size: 11px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background: transparent;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-nav:hover {
            background: var(--gold);
            color: var(--bg);
        }

        /* ── Dropdown User ──────────────────────── */
        .user-dropdown { position: relative; }
        .user-dropdown__menu {
            display: none;
            position: absolute; top: calc(100% + 12px); right: 0;
            min-width: 180px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            padding: 8px 0;
            z-index: 300;
        }
        /* Aktif via JS — BUKAN hover */
        .user-dropdown__menu.open { display: block; }

        .user-dropdown__menu a,
        .user-dropdown__menu button {
            display: block; width: 100%;
            padding: 10px 18px;
            font-size: 12px;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            text-align: left;
            background: none; border: none; cursor: pointer;
            transition: var(--transition);
        }
        .user-dropdown__menu a:hover,
        .user-dropdown__menu button:hover {
            color: var(--gold);
            background: rgba(200,169,110,0.06);
        }
        .user-dropdown__menu hr {
            border: none;
            border-top: 1px solid var(--border);
            margin: 6px 0;
        }

        /* ══════════════════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════════════════ */
        .flash {
            position: fixed; top: 80px; right: 24px; z-index: 200;
            max-width: 340px;
            padding: 14px 20px;
            font-size: 13px;
            letter-spacing: 0.04em;
            border-left: 3px solid;
            animation: slideIn 0.4s ease, fadeOut 0.4s ease 3.6s forwards;
        }
        .flash--success {
            background: rgba(20,18,16,0.95);
            border-color: var(--gold);
            color: var(--cream);
        }
        .flash--error {
            background: rgba(20,18,16,0.95);
            border-color: var(--red);
            color: var(--cream);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(20px); }
            to   { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeOut {
            to { opacity: 0; transform: translateX(20px); }
        }

        /* ══════════════════════════════════════════
           MAIN CONTENT
        ══════════════════════════════════════════ */
        .main { padding-top: 68px; min-height: calc(100vh - 68px); }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
        .footer {
            border-top: 1px solid var(--border);
            padding: 56px 48px 32px;
            margin-top: 96px;
        }
        .footer__grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer__brand-name {
            font-family: var(--font-display);
            font-size: 28px;
            color: var(--cream);
            margin-bottom: 16px;
        }
        .footer__brand-name span { color: var(--gold); }
        .footer__tagline {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.8;
            max-width: 260px;
        }
        .footer__heading {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 20px;
        }
        .footer__links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer__links a {
            font-size: 13px;
            color: var(--text-muted);
            transition: color var(--transition);
        }
        .footer__links a:hover { color: var(--cream); }
        .footer__bottom {
            border-top: 1px solid var(--border);
            padding-top: 24px;
            display: flex; justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.06em;
        }

        /* ══════════════════════════════════════════
           UTILITIES
        ══════════════════════════════════════════ */
        .container {
            max-width: 1280px;
            margin: 0 auto;
            padding: 0 48px;
        }
        .section-label {
            font-size: 10px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }
        .section-title {
            font-family: var(--font-display);
            font-size: clamp(32px, 4vw, 52px);
            font-weight: 400;
            line-height: 1.15;
            color: var(--cream);
        }
        .section-title em { font-style: italic; color: var(--gold); }
        .divider {
            width: 48px; height: 1px;
            background: var(--gold);
            margin: 24px 0;
        }

        /* ── Buttons ────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 13px 28px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            cursor: pointer;
            transition: var(--transition);
            border: none;
        }
        .btn-primary {
            background: var(--gold);
            color: var(--bg);
        }
        .btn-primary:hover {
            background: var(--cream);
        }
        .btn-outline {
            background: transparent;
            border: 1px solid var(--gold);
            color: var(--gold);
        }
        .btn-outline:hover {
            background: var(--gold);
            color: var(--bg);
        }
        .btn-ghost {
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text-muted);
        }
        .btn-ghost:hover {
            border-color: var(--cream-dim);
            color: var(--cream);
        }
        .btn-danger {
            background: transparent;
            border: 1px solid rgba(192,57,43,0.4);
            color: #e57d6d;
        }
        .btn-danger:hover {
            background: rgba(192,57,43,0.15);
        }

        /* ── Form ───────────────────────────────── */
        .form-group { margin-bottom: 24px; }
        .form-label {
            display: block;
            font-size: 10px;
            font-weight: 500;
            letter-spacing: 0.16em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 8px;
        }
        .form-control {
            width: 100%;
            padding: 12px 16px;
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--border);
            color: var(--cream);
            font-size: 14px;
            font-family: var(--font-body);
            transition: border-color var(--transition);
            outline: none;
            border-radius: var(--radius-sm);
        }
        .form-control:focus {
            border-color: var(--gold);
            background: rgba(200,169,110,0.04);
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-error {
            margin-top: 6px;
            font-size: 12px;
            color: #e57d6d;
        }
        select.form-control option { background: var(--bg-card); }

        /* ── Star rating input ──────────────────── */
        .star-rating {
            display: flex; flex-direction: row-reverse;
            gap: 4px;
        }
        .star-rating input { display: none; }
        .star-rating label {
            font-size: 28px;
            color: var(--border);
            cursor: pointer;
            transition: color 0.15s;
        }
        .star-rating input:checked ~ label,
        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color: var(--gold);
        }

        /* ── Card produk ────────────────────────── */
        .product-card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            overflow: hidden;
            transition: var(--transition);
            position: relative;
        }
        .product-card:hover { border-color: rgba(200,169,110,0.3); }
        .product-card__img {
            width: 100%; aspect-ratio: 4/3;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .product-card:hover .product-card__img { transform: scale(1.04); }
        .product-card__img-wrap { overflow: hidden; position: relative; }
        .product-card__badge {
            position: absolute; top: 12px; left: 12px;
            padding: 4px 10px;
            font-size: 9px; letter-spacing: 0.14em; text-transform: uppercase;
            background: var(--gold); color: var(--bg);
        }
        .product-card__body { padding: 18px 20px 20px; }
        .product-card__category {
            font-size: 10px; letter-spacing: 0.16em;
            text-transform: uppercase; color: var(--text-muted);
            margin-bottom: 6px;
        }
        .product-card__name {
            font-family: var(--font-display);
            font-size: 18px; font-weight: 500;
            color: var(--cream);
            margin-bottom: 8px;
            line-height: 1.3;
        }
        .product-card__price {
            font-size: 15px; font-weight: 500;
            color: var(--gold);
        }
        .product-card__footer {
            display: flex; justify-content: space-between; align-items: center;
            padding: 12px 20px;
            border-top: 1px solid var(--border);
        }
        .stars { display: flex; gap: 2px; }
        .star { font-size: 12px; color: var(--gold); }
        .star.empty { color: var(--border); }
        .wishlist-btn {
            width: 32px; height: 32px;
            background: none; border: none; cursor: pointer;
            color: var(--text-muted);
            transition: var(--transition);
            display: flex; align-items: center; justify-content: center;
        }
        .wishlist-btn:hover, .wishlist-btn.active { color: #e57d6d; }
        .wishlist-btn svg { width: 18px; height: 18px; }

        /* ── Pagination ─────────────────────────── */
        .pagination {
            display: flex; justify-content: center; align-items: center;
            gap: 4px; padding: 48px 0;
        }
        .page-item a, .page-item span {
            display: flex; align-items: center; justify-content: center;
            width: 38px; height: 38px;
            font-size: 13px;
            border: 1px solid var(--border);
            color: var(--text-muted);
            transition: var(--transition);
        }
        .page-item a:hover { border-color: var(--gold); color: var(--gold); }
        .page-item.active span { background: var(--gold); border-color: var(--gold); color: var(--bg); }

        /* ── Notif bell dropdown ────────────────── */
        .notif-dropdown { position: relative; }
        .notif-panel {
            display: none;
            position: absolute; top: calc(100% + 12px); right: 0;
            width: 320px;
            background: var(--bg-card);
            border: 1px solid var(--border);
            max-height: 420px; overflow-y: auto;
            z-index: 300;
        }
        /* Aktif via JS — BUKAN hover */
        .notif-panel.open { display: block; }

        .notif-panel__header {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            display: flex; justify-content: space-between; align-items: center;
        }
        .notif-panel__header h4 {
            font-size: 11px; letter-spacing: 0.14em; text-transform: uppercase;
            color: var(--cream);
        }
        .notif-panel__header a {
            font-size: 11px; color: var(--gold);
        }
        .notif-item {
            padding: 14px 18px;
            border-bottom: 1px solid var(--border);
            transition: background var(--transition);
            display: block;
        }
        .notif-item:hover { background: var(--bg-hover); }
        .notif-item.unread { border-left: 2px solid var(--gold); }
        .notif-item__title {
            font-size: 13px; color: var(--cream);
            margin-bottom: 4px;
        }
        .notif-item__time {
            font-size: 11px; color: var(--text-muted);
        }
        .notif-empty {
            padding: 32px 18px;
            text-align: center;
            font-size: 13px; color: var(--text-muted);
        }
    </style>

    @stack('styles')
</head>
<body>

{{-- ═══════════════════════════════ NAVBAR ═══════════════════════════════ --}}
<nav class="navbar">
    <a href="{{ route('home') }}" class="navbar__logo">Casa<span>Forma</span></a>

    <ul class="navbar__nav">
        <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Beranda</a></li>
        <li><a href="{{ route('products.index') }}" class="{{ request()->routeIs('products.*') ? 'active' : '' }}">Katalog</a></li>
        <li><a href="{{ route('products.index', ['category' => 'sofa']) }}">Sofa</a></li>
        <li><a href="{{ route('products.index', ['category' => 'meja']) }}">Meja</a></li>
        <li><a href="{{ route('products.index', ['category' => 'kursi']) }}">Kursi</a></li>
    </ul>

    <div class="navbar__actions">
        @auth
            {{-- Notifikasi --}}
            <div class="notif-dropdown">
                <div class="navbar__icon" id="notif-toggle" aria-label="Notifikasi">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @php $unreadCount = auth()->user()->unreadNotifications->count(); @endphp
                    @if($unreadCount > 0)
                        <span class="badge">{{ $unreadCount > 9 ? '9+' : $unreadCount }}</span>
                    @endif
                </div>

                <div class="notif-panel" id="notif-panel">
                    <div class="notif-panel__header">
                        <h4>Notifikasi</h4>
                        @if($unreadCount > 0)
                            <a href="{{ route('notifications.readAll') }}" onclick="event.preventDefault(); document.getElementById('read-all-form').submit()">Tandai Semua Dibaca</a>
                            <form id="read-all-form" action="{{ route('notifications.readAll') }}" method="POST" style="display:none">@csrf</form>
                        @endif
                    </div>
                    @forelse(auth()->user()->notifications->take(10) as $notif)
                        <a href="{{ route('notifications.read', $notif->id) }}" class="notif-item {{ $notif->read_at ? '' : 'unread' }}">
                            <p class="notif-item__title">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                            <p class="notif-item__time">{{ $notif->created_at->diffForHumans() }}</p>
                        </a>
                    @empty
                        <div class="notif-empty">Belum ada notifikasi</div>
                    @endforelse
                </div>
            </div>

            {{-- Wishlist --}}
            <a href="{{ route('wishlist.index') }}" class="navbar__icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
                @php $wCount = auth()->user()->wishlists()->count(); @endphp
                @if($wCount > 0)<span class="badge">{{ $wCount }}</span>@endif
            </a>

            {{-- User dropdown --}}
            <div class="user-dropdown">
                <div class="navbar__icon" id="user-toggle" aria-label="Menu pengguna">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="user-dropdown__menu" id="user-menu">
                    <span style="padding:10px 18px;display:block;font-size:11px;color:var(--gold)">{{ auth()->user()->name }}</span>
                    <hr>
                    <a href="{{ route('profile.edit') }}">Profil Saya</a>
                    @if(auth()->user()->isSeller())
                        <a href="{{ route('seller.dashboard') }}">Dashboard Seller</a>
                    @endif
                    <hr>
                    <button form="logout-form" type="submit">Keluar</button>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
                </div>
            </div>
        @else
            <a href="{{ route('login') }}" class="btn-nav">Masuk</a>
            <a href="{{ route('register') }}" class="btn btn-primary" style="padding:9px 20px">Daftar</a>
        @endauth
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
    <div class="flash flash--success">{{ session('success') }}</div>
@endif
@if(session('error'))
    <div class="flash flash--error">{{ session('error') }}</div>
@endif

<main class="main">
    @yield('content')
</main>

<footer class="footer">
    <div class="footer__grid">
        <div>
            <div class="footer__brand-name">Casa<span>Forma</span></div>
            <p class="footer__tagline">Menghadirkan keindahan dan kenyamanan ke setiap sudut rumah Anda melalui mabel pilihan berkualitas tinggi.</p>
        </div>
        <div>
            <p class="footer__heading">Navigasi</p>
            <ul class="footer__links">
                <li><a href="{{ route('products.index') }}">Katalog Produk</a></li>
                <li><a href="{{ route('products.index', ['category' => 'sofa']) }}">Sofa & Kursi</a></li>
                <li><a href="{{ route('products.index', ['category' => 'meja']) }}">Meja & Lemari</a></li>
            </ul>
        </div>
        <div>
            <p class="footer__heading">Akun</p>
            <ul class="footer__links">
                @auth
                    <li><a href="{{ route('profile.edit') }}">Profil Saya</a></li>
                    <li><a href="{{ route('wishlist.index') }}">Wishlist</a></li>
                @else
                    <li><a href="{{ route('login') }}">Masuk</a></li>
                    <li><a href="{{ route('register') }}">Daftar</a></li>
                @endauth
            </ul>
        </div>
        <div>
            <p class="footer__heading">Bantuan</p>
            <ul class="footer__links">
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat & Ketentuan</a></li>
                <li><a href="#">Hubungi Kami</a></li>
            </ul>
        </div>
    </div>
    <div class="footer__bottom">
        <span>© {{ date('Y') }} CasaForma. Hak cipta dilindungi.</span>
        <span>Dibuat dengan cinta untuk rumah Anda</span>
    </div>
</footer>

{{-- ═══════════════════════════════ DROPDOWN SCRIPT ═══════════════════════════════ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Helper: tutup semua dropdown
    function closeAll() {
        document.querySelectorAll('.user-dropdown__menu, .notif-panel').forEach(function (el) {
            el.classList.remove('open');
        });
    }

    // Toggle notifikasi
    var notifToggle = document.getElementById('notif-toggle');
    var notifPanel  = document.getElementById('notif-panel');
    if (notifToggle && notifPanel) {
        notifToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = notifPanel.classList.contains('open');
            closeAll();
            if (!isOpen) notifPanel.classList.add('open');
        });
        // Klik dalam panel tidak menutup dropdown
        notifPanel.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Toggle user menu
    var userToggle = document.getElementById('user-toggle');
    var userMenu   = document.getElementById('user-menu');
    if (userToggle && userMenu) {
        userToggle.addEventListener('click', function (e) {
            e.stopPropagation();
            var isOpen = userMenu.classList.contains('open');
            closeAll();
            if (!isOpen) userMenu.classList.add('open');
        });
        // Klik dalam menu (misal link/button) tetap bisa berjalan, tapi tidak menutup sebelum navigasi
        userMenu.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    }

    // Klik di luar = tutup semua
    document.addEventListener('click', function () {
        closeAll();
    });

    // Tekan Escape = tutup semua
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeAll();
    });
});
</script>

@stack('scripts')
</body>
</html>