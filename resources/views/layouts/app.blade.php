<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'CasaForma') — Furniture Marketplace</title>

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
            --danger:    #c0392b;
            --success:   #27ae60;

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
        .navbar-brand {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 500;
            letter-spacing: 0.08em;
            color: var(--cream);
            text-decoration: none;
        }
        .navbar-brand span { color: var(--gold); }

        .navbar-links {
            display: flex; align-items: center; gap: 36px;
            list-style: none;
        }
        .navbar-links a {
            font-size: 12px;
            font-weight: 400;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: var(--text-muted);
            text-decoration: none;
            transition: color var(--transition);
        }
        .navbar-links a:hover,
        .navbar-links a.active { color: var(--gold); }

        .navbar-actions {
            display: flex; align-items: center; gap: 20px;
        }
        .navbar-actions a {
            color: var(--cream-dim);
            text-decoration: none;
            font-size: 0.875rem;
            display: flex; align-items: center; gap: 0.35rem;
            width: 38px; height: 38px;
            justify-content: center;
            transition: color var(--transition);
            position: relative;
        }
        .navbar-actions a:hover { color: var(--gold); }
        .navbar-actions a svg { width: 20px; height: 20px; }

        /* Text links in navbar-actions (Pesanan, Laporan) */
        .navbar-actions a.text-link {
            width: auto; height: auto;
            font-size: 12px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        .cart-badge {
            position: absolute; top: 4px; right: 4px;
            width: 16px; height: 16px;
            background: var(--gold);
            color: var(--bg);
            border-radius: 50%;
            font-size: 9px;
            font-weight: 600;
            display: inline-flex; align-items: center; justify-content: center;
        }

        /* ══════════════════════════════════════════
           PAGE WRAPPER
        ══════════════════════════════════════════ */
        .page-wrapper {
            max-width: 1280px;
            margin: 0 auto;
            padding: 2.5rem 48px;
            padding-top: calc(68px + 2.5rem);
        }

        /* ══════════════════════════════════════════
           FLASH MESSAGES
        ══════════════════════════════════════════ */
        .alert {
            padding: 14px 20px;
            font-size: 13px;
            letter-spacing: 0.04em;
            border-left: 3px solid;
            margin-bottom: 1.5rem;
            background: rgba(20,18,16,0.95);
            color: var(--cream);
        }
        .alert-success { border-color: var(--gold); }
        .alert-error   { border-color: var(--danger); }
        .alert-info    { border-color: var(--gold-dim); }

        /* ══════════════════════════════════════════
           BUTTONS
        ══════════════════════════════════════════ */
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
            text-decoration: none;
            font-family: var(--font-body);
        }
        .btn-primary {
            background: var(--gold);
            color: var(--bg);
        }
        .btn-primary:hover { background: var(--cream); }

        .btn-accent {
            background: var(--gold);
            color: var(--bg);
        }
        .btn-accent:hover { background: var(--gold-dim); }

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
        .btn-danger:hover { background: rgba(192,57,43,0.15); }

        .btn-sm { padding: 8px 16px; font-size: 10px; }
        .btn-lg { padding: 16px 36px; font-size: 13px; }

        /* ══════════════════════════════════════════
           SECTION HEADING
        ══════════════════════════════════════════ */
        .section-heading {
            font-family: var(--font-display);
            font-size: clamp(28px, 4vw, 48px);
            font-weight: 400;
            color: var(--cream);
            margin-bottom: 0.25rem;
            line-height: 1.15;
        }
        .section-heading em { font-style: italic; color: var(--gold); }
        .section-subheading {
            color: var(--text-muted);
            font-size: 13px;
            letter-spacing: 0.04em;
            margin-bottom: 2rem;
        }
        .section-label {
            font-size: 10px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 12px;
        }
        .divider {
            width: 48px; height: 1px;
            background: var(--gold);
            margin: 24px 0;
        }

        /* ══════════════════════════════════════════
           CARD
        ══════════════════════════════════════════ */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border);
            border-radius: var(--radius-sm);
            overflow: hidden;
            transition: var(--transition);
        }
        .card:hover { border-color: rgba(200,169,110,0.3); }
        .card-body { padding: 1.5rem; }

        /* ══════════════════════════════════════════
           TABLE
        ══════════════════════════════════════════ */
        table.cf-table { width: 100%; border-collapse: collapse; font-size: 13px; }
        .cf-table th {
            text-align: left;
            padding: 12px 16px;
            font-weight: 500;
            color: var(--text-muted);
            border-bottom: 1px solid var(--border);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .cf-table td {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            vertical-align: middle;
            color: var(--text);
        }
        .cf-table tr:last-child td { border-bottom: none; }
        .cf-table tr:hover td { background: var(--bg-hover); }

        /* ══════════════════════════════════════════
           BADGE / STATUS
        ══════════════════════════════════════════ */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            font-size: 9px;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            font-weight: 500;
        }
        .badge-pending   { background: rgba(200,169,110,0.15); color: var(--gold); }
        .badge-paid      { background: rgba(39,174,96,0.15);   color: #5ddc8e; }
        .badge-shipped   { background: rgba(52,152,219,0.15);  color: #74b9e8; }
        .badge-delivered { background: rgba(39,174,96,0.15);   color: #5ddc8e; }
        .badge-cancelled { background: rgba(192,57,43,0.15);   color: #e57d6d; }

        /* ══════════════════════════════════════════
           FOOTER
        ══════════════════════════════════════════ */
        footer {
            border-top: 1px solid var(--border);
            padding: 56px 48px 32px;
            margin-top: 96px;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 48px;
            margin-bottom: 48px;
        }
        .footer-brand {
            font-family: var(--font-display);
            font-size: 28px;
            color: var(--cream);
            margin-bottom: 16px;
        }
        .footer-brand span { color: var(--gold); }
        .footer-tagline {
            font-size: 13px;
            color: var(--text-muted);
            line-height: 1.8;
            max-width: 260px;
        }
        .footer-heading {
            font-size: 10px;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: var(--gold);
            margin-bottom: 20px;
        }
        .footer-links { list-style: none; display: flex; flex-direction: column; gap: 10px; }
        .footer-links a {
            font-size: 13px;
            color: var(--text-muted);
            text-decoration: none;
            display: block;
            transition: color var(--transition);
        }
        .footer-links a:hover { color: var(--cream); }
        .footer-bottom {
            border-top: 1px solid var(--border);
            padding-top: 24px;
            display: flex; justify-content: space-between;
            font-size: 11px;
            color: var(--text-muted);
            letter-spacing: 0.06em;
        }
    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar">
    <a class="navbar-brand" href="/">Casa<span>Forma</span></a>
    <ul class="navbar-links">
        <li><a href="{{ route('products.index') }}">Koleksi</a></li>
        <li><a href="{{ route('search.index') }}" class="{{ request()->routeIs('search.*') ? 'active' : '' }}">Cari Furniture</a></li>
        <li><a href="#">Ruang Inspirasi</a></li>
        <li><a href="#">Tentang Kami</a></li>
    </ul>
    <div class="navbar-actions">
        <a href="{{ route('search.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="m21 21-4.35-4.35"/></svg>
        </a>
        <a href="{{ route('cart.index') }}">
            <svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            @if(session('cart') && count(session('cart')) > 0)
                <span class="cart-badge">{{ count(session('cart')) }}</span>
            @endif
        </a>
        @auth
        <a href="{{ route('orders.index') }}" class="text-link">Pesanan</a>
        <a href="{{ route('reports.index') }}" class="text-link">Laporan</a>
        @endauth
    </div>
</nav>

<main>
    <div class="page-wrapper">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif
        @if(session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        @yield('content')
    </div>
</main>

<footer>
    <div class="footer-grid">
        <div>
            <div class="footer-brand">Casa<span>Forma</span></div>
            <p class="footer-tagline">Furniture pilihan dengan desain artistik untuk rumah Anda yang bermakna.</p>
        </div>
        <div>
            <p class="footer-heading">Kebijakan</p>
            <ul class="footer-links">
                <li><a href="#">Kebijakan Privasi</a></li>
                <li><a href="#">Syarat &amp; Ketentuan</a></li>
                <li><a href="#">Pengembalian</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-heading">Bantuan</p>
            <ul class="footer-links">
                <li><a href="#">FAQ</a></li>
                <li><a href="#">Hubungi Kami</a></li>
                <li><a href="#">Panduan Ukuran</a></li>
            </ul>
        </div>
        <div>
            <p class="footer-heading">Ikuti Kami</p>
            <ul class="footer-links">
                <li><a href="#">Instagram</a></li>
                <li><a href="#">Pinterest</a></li>
                <li><a href="#">TikTok</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <span>© 2024 CasaForma. Dibuat dengan ❤ untuk rumah Indonesia.</span>
        <span>Didesain dengan Cinta Digital Tinggi</span>
    </div>
</footer>

@stack('scripts')
</body>
</html>