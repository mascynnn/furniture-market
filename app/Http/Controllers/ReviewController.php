<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Notifications\NewReviewNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Simpan review baru.
     */
    public function store(Request $request, Product $product): RedirectResponse
    {
        $user = auth()->user();

        // FIX: seller tidak boleh mereview produk miliknya sendiri
        if ($product->user_id === $user->id) {
            return back()->with('error', 'Anda tidak dapat mengulas produk milik sendiri.');
        }

        // Pastikan user belum pernah review produk ini
        $alreadyReviewed = Review::where('user_id', $user->id)
                                 ->where('product_id', $product->id)
                                 ->exists();

        if ($alreadyReviewed) {
            return back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini.');
        }

        $validated = $request->validate([
            'rating'  => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ], [
            'rating.required' => 'Pilih rating bintang.',
            'rating.min'      => 'Rating minimal 1 bintang.',
            'rating.max'      => 'Rating maksimal 5 bintang.',
        ]);

        $review = Review::create([
            'user_id'    => $user->id,
            'product_id' => $product->id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'] ?? null,
        ]);

        // Update rata-rata rating di tabel produk
        $product->recalculateRating();

        // FIX: load relasi 'user' agar NewReviewNotification bisa akses $review->user->name
        //      tanpa lazy-load N+1 yang bisa gagal jika relasi belum di-load
        $review->load('user');

        // Kirim notifikasi ke seller
        $product->seller->notify(new NewReviewNotification($review, $product));

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    /**
     * Hapus review milik user sendiri.
     */
    public function destroy(Review $review): RedirectResponse
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        // FIX: simpan relasi produk SEBELUM delete agar masih bisa diakses
        $product = $review->product;
        $review->delete();

        $product->recalculateRating();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}