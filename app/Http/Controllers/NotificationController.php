<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Halaman semua notifikasi */
    public function index(): View
    {
        // Ambil notifikasi (read + unread) dengan pagination agar tidak berat
        $notifications = auth()->user()
                               ->notifications()
                               ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /** Tandai satu notifikasi sebagai dibaca dan redirect ke URL terkait */
    public function read(string $id): RedirectResponse
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        // FIX: hanya markAsRead jika belum dibaca (idempoten & hemat query)
        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        // FIX: pastikan 'url' ada di data; fallback ke route('home') jika tidak
        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }

    /** Tandai semua notifikasi sebagai dibaca */
    public function readAll(Request $request): RedirectResponse
    {
        // FIX: gunakan markAsRead() pada collection — sudah efisien di Laravel
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}