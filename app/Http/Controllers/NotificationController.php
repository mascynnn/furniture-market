<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /** Halaman semua notifikasi */
    public function index()
    {
        // Tandai semua sebagai dibaca saat buka halaman (opsional, bisa di-comment)
        // auth()->user()->unreadNotifications->markAsRead();

        return view('notifications.index');
    }

    /** Tandai satu notifikasi sebagai dibaca dan redirect ke URL terkait */
    public function read(string $id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        $url = $notification->data['url'] ?? route('home');

        return redirect($url);
    }

    /** Tandai semua notifikasi sebagai dibaca */
    public function readAll(Request $request)
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }
}
