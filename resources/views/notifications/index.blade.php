@extends('layouts.app')
@section('title', 'Notifikasi — CasaForma')

@push('styles')
<style>
.notif-page { padding: 64px 0; }
.notif-page__header {
    display: flex; justify-content: space-between; align-items: flex-end;
    margin-bottom: 48px;
    padding-bottom: 32px;
    border-bottom: 1px solid var(--border);
}
.notif-page__header h1 {
    font-family: var(--font-display);
    font-size: clamp(32px, 4vw, 52px);
    font-weight: 300; color: var(--cream);
    line-height: 1.1;
}
.notif-page__header h1 em { font-style: italic; color: var(--gold); }
.notif-list { display: flex; flex-direction: column; gap: 0; }
.notif-full-item {
    display: flex; gap: 24px; align-items: flex-start;
    padding: 24px 0;
    border-bottom: 1px solid var(--border);
    transition: var(--transition);
    cursor: pointer;
}
.notif-full-item:hover { background: rgba(255,255,255,0.02); }
.notif-full-item.unread .notif-icon { color: var(--gold); }
.notif-icon {
    width: 44px; height: 44px; flex-shrink: 0;
    border: 1px solid var(--border);
    display: flex; align-items: center; justify-content: center;
    color: var(--text-muted);
    border-radius: 50%;
}
.notif-icon svg { width: 20px; height: 20px; }
.notif-content { flex: 1; }
.notif-content__title {
    font-size: 15px; color: var(--cream);
    margin-bottom: 6px; line-height: 1.4;
}
.notif-full-item.unread .notif-content__title {
    color: var(--white);
    font-weight: 500;
}
.notif-content__body {
    font-size: 13px; color: var(--text-muted);
    line-height: 1.6; margin-bottom: 8px;
}
.notif-content__time {
    font-size: 11px; color: var(--text-muted);
    letter-spacing: 0.06em;
}
.notif-dot {
    width: 8px; height: 8px;
    background: var(--gold);
    border-radius: 50%;
    flex-shrink: 0;
    margin-top: 8px;
}
.notif-empty {
    padding: 120px 0; text-align: center;
}
.notif-empty__icon {
    font-size: 48px; margin-bottom: 20px; opacity: 0.3;
}
.notif-empty h3 {
    font-family: var(--font-display);
    font-size: 32px; color: var(--cream); margin-bottom: 12px;
}
.notif-empty p { font-size: 14px; color: var(--text-muted); }
</style>
@endpush

@section('content')
<div class="notif-page">
    <div class="container" style="max-width:800px">
        <div class="notif-page__header">
            <div>
                <p class="section-label">Pusat Informasi</p>
                <h1>Notifikasi <em>Anda</em></h1>
            </div>
            <div style="display:flex;gap:12px;align-items:center">
                @if(auth()->user()->unreadNotifications->count() > 0)
                    <span style="font-size:12px;color:var(--text-muted)">
                        {{ auth()->user()->unreadNotifications->count() }} belum dibaca
                    </span>
                    <form action="{{ route('notifications.readAll') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-outline" style="padding:8px 18px;font-size:10px">
                            Tandai Semua Dibaca
                        </button>
                    </form>
                @endif
            </div>
        </div>

        @php $notifications = auth()->user()->notifications()->paginate(20); @endphp

        @if($notifications->count() > 0)
            <div class="notif-list">
                @foreach($notifications as $notif)
                    <a href="{{ route('notifications.read', $notif->id) }}"
                       class="notif-full-item {{ $notif->read_at ? '' : 'unread' }}">

                        <div class="notif-icon">
                            @php $type = $notif->data['type'] ?? 'info'; @endphp
                            @if($type === 'review')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            @elseif($type === 'order')
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            @else
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            @endif
                        </div>

                        <div class="notif-content">
                            <p class="notif-content__title">{{ $notif->data['title'] ?? 'Notifikasi' }}</p>
                            @if(!empty($notif->data['body']))
                                <p class="notif-content__body">{{ $notif->data['body'] }}</p>
                            @endif
                            <p class="notif-content__time">{{ $notif->created_at->diffForHumans() }}</p>
                        </div>

                        @if(!$notif->read_at)
                            <div class="notif-dot"></div>
                        @endif
                    </a>
                @endforeach
            </div>

            @if($notifications->hasPages())
                <div class="pagination">{{ $notifications->links('vendor.pagination.casaforma') }}</div>
            @endif
        @else
            <div class="notif-empty">
                <div class="notif-empty__icon">🔔</div>
                <h3>Belum Ada Notifikasi</h3>
                <p>Kami akan memberitahu Anda tentang pesanan, ulasan, dan penawaran terbaru</p>
            </div>
        @endif
    </div>
</div>
@endsection
