<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Review;
use App\Notifications\NewReviewNotification;
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
    public function store(Request $request, Product $product)
    {
        // Pastikan user belum pernah review produk ini
        $alreadyReviewed = Review::where('user_id', auth()->id())
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
            'user_id'    => auth()->id(),
            'product_id' => $product->id,
            'rating'     => $validated['rating'],
            'comment'    => $validated['comment'],
        ]);

        // Update rata-rata rating di tabel produk
        $product->recalculateRating();

        // Kirim notifikasi ke seller
        $product->seller->notify(new NewReviewNotification($review, $product));

        return back()->with('success', 'Ulasan berhasil dikirim. Terima kasih!');
    }

    /**
     * Hapus review milik user sendiri.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus ulasan ini.');
        }

        $product = $review->product;
        $review->delete();

        $product->recalculateRating();

        return back()->with('success', 'Ulasan berhasil dihapus.');
    }
}
