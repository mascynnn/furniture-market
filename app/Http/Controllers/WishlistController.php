<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman daftar wishlist user.
     */
    public function index()
    {
        $wishlists = Wishlist::with(['product.primaryImage', 'product.seller'])
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle wishlist (tambah / hapus).
     * Bisa dipanggil via AJAX atau form biasa.
     */
    public function toggle(Request $request, Product $product)
    {
        $user     = auth()->user();
        $wishlist = Wishlist::where('user_id', $user->id)
                           ->where('product_id', $product->id)
                           ->first();

        if ($wishlist) {
            $wishlist->delete();
            $status  = false;
            $message = 'Produk dihapus dari wishlist.';
        } else {
            Wishlist::create([
                'user_id'    => $user->id,
                'product_id' => $product->id,
            ]);
            $status  = true;
            $message = 'Produk ditambahkan ke wishlist!';
        }

        // Jika request Ajax (dari tombol hati di kartu produk)
        if ($request->expectsJson()) {
            return response()->json([
                'wishlisted' => $status,
                'message'    => $message,
                'count'      => Wishlist::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Hapus satu item dari wishlist.
     */
    public function destroy(Wishlist $wishlist)
    {
        if ($wishlist->user_id !== auth()->id()) {
            abort(403);
        }

        $wishlist->delete();

        return back()->with('success', 'Produk dihapus dari wishlist.');
    }

    /**
     * Hapus semua wishlist milik user.
     */
    public function clear()
    {
        Wishlist::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Semua wishlist telah dihapus.');
    }
}
