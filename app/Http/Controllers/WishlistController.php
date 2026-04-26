<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\View\View;

class WishlistController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth'),
        ];
    }

    /**
     * Halaman daftar wishlist user.
     */
    public function index(): View
    {
        // FIX: sertakan 'product.images' di sini (bukan di model Wishlist)
        //      agar eager loading terkontrol dan tidak double-load
        $wishlists = Wishlist::with(['product.images', 'product.seller'])
                            ->where('user_id', auth()->id())
                            ->latest()
                            ->paginate(12);

        return view('wishlist.index', compact('wishlists'));
    }

    /**
     * Toggle wishlist (tambah / hapus).
     * Bisa dipanggil via AJAX atau form biasa.
     */
    public function toggle(Request $request, Product $product): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

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

        if ($request->expectsJson()) {
            return response()->json([
                'wishlisted' => $status,
                'message'    => $message,
                // FIX: hitung ulang count setelah toggle
                'count'      => Wishlist::where('user_id', $user->id)->count(),
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Hapus satu item dari wishlist.
     */
    public function destroy(Wishlist $wishlist): RedirectResponse
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
    public function clear(): RedirectResponse
    {
        Wishlist::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Semua wishlist telah dihapus.');
    }
}